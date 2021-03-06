<?php

namespace d3system\yii2\web;


use cornernote\returnurl\ReturnUrl;
use eaBlankonThema\widget\ThButton;
use eaBlankonThema\widget\ThButtonDropDown;
use Exception;
use Yii;
use yii\web\View;

/**
 *
 * @property string $leftMenu
 */
class D3SystemView extends View
{

    /**
     * @var string[]
     */
    public $leftMenuFiles = [];

    /**
     * @var string
     */
    public $defaultLeftMenu = 'main';

    public $wikiViewUrl = ['/wiki/content/view'];

    /** @var string */
    private $leftMenuCode;

    private $pageHeader = '';
    private $pageFooter = '';
    private $pageHeaderDescription = '';
    private $pageIcon = '';

    private $pageButtons = [];

    private $pageButtonsRight = [];
    private $finalPageButtonsRight = [];
    private $pageWiki = '';

    private $breadCrumb = [];

    private $settingButtonUrl;
    private $settingButtonTooltip;
    /**
     * @var array = [
     *   [
     *      'lable' => 'label name'
     *      'url' => [
     *          'excel',
     *           'id' => 12
     *      ]
     *   ]
     * ]
     */
    private $exportButtonList = [];


    public function init()
    {
        $this->setLeftMenu($this->defaultLeftMenu);
        parent::init();
    }

    /**
     * @return array
     */
    public function getPageButtons(): array
    {
        if(isset($this->params['pageButtons'])){
            return array_merge([$this->params['pageButtons']], $this->pageButtons);
        }
        return $this->pageButtons;
    }

    public function addBreadCrumb(array $url, string $label): void
    {
        $this->breadCrumb[$label] = $url;
    }

    public function getBreadCrumb(): array
    {
        return $this->breadCrumb;
    }
    /**
     * @param string $pageButton
     */
    public function addPageButtons(string $pageButton): void
    {
        $this->pageButtons[] = $pageButton;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getPageButtonsRight(): array
    {
        if($this->finalPageButtonsRight){
            return $this->finalPageButtonsRight;
        }

        $this->finalPageButtonsRight = $this->pageButtonsRight;
        if(isset($this->params['pageButtonsRight'])){
            $this->finalPageButtonsRight[] = $this->params['pageButtonsRight'];
        }
        if(count($this->exportButtonList) === 1){
            $this->finalPageButtonsRight[] = ThButton::widget([
                'label' => Yii::t('d3system','Export')
                    . ': '
                    . $this->exportButtonList[0]['label'],
                'icon' => ThButton::ICON_DOWNLOAD,
                'type' => ThButton::TYPE_SUCCESS,
                'link' => $this->exportButtonList[0]['url']
            ]);
        }elseif(count($this->exportButtonList) > 1){
            $this->finalPageButtonsRight[] = ThButtonDropDown::widget([
                'label' => Yii::t('d3system','Export'),
                'icon' => ThButton::ICON_DOWNLOAD,
                'type' => ThButton::TYPE_SUCCESS,
                'items' => $this->exportButtonList
            ]);
        }

        if($this->settingButtonUrl){
            $this->finalPageButtonsRight[] = ThButton::widget([
                'type' => ThButton::TYPE_DEFAULT,
                'tooltip' => $this->settingButtonTooltip,
                'link' => $this->settingButtonUrl,
                'icon' => ThButton::ICON_COG,
            ]);
        }

        return $this->finalPageButtonsRight;
    }

    /**
     * @param string $pageButtonsRight
     */
    public function addPageButtonsRight(string $pageButtonsRight): void
    {
        $this->pageButtonsRight[] = $pageButtonsRight;
    }

    /**
     * @param string $label
     * @param array $url
     */
    public function addExportButtonItem(string $label, array $url): void
    {
        $this->exportButtonList[] = [
            'label' => $label,
            'url' => $url
        ];
    }

    public function setSettingButton(array $url, string $tooltip = ''): void
    {
        $this->settingButtonUrl = $url;
        $this->settingButtonTooltip = $tooltip;
    }

    /**
     * @return string
     * @deprecated use self::getWikiViewUrl()
     */
    public function getPageWiki(): string
    {
        return $this->params['pageWiki'] ?? $this->pageWiki;
    }

    /**
     * @return array
     */
    public function getWikiViewUrl(): array
    {
        if(!$id = $this->params['pageWiki'] ?? $this->pageWiki){
            return [];
        }
        $url = $this->wikiViewUrl;
        $url['id'] = $id;
        $url['ru'] = ReturnUrl::getToken($this->title);
        return $url;
    }

    /**
     * @param string $pageWiki
     */
    public function setPageWiki(string $pageWiki): void
    {
        $this->pageWiki = $pageWiki;
    }

    /**
     * @param string $menuCode
     * @return array
     */
    public function getLeftMenu(string $menuCode = ''): array
    {
        if(!$menuCode){
            $menuCode = $this->leftMenuCode;
        }
        if(isset($this->leftMenuFiles[$menuCode])){
            return require Yii::getAlias($this->leftMenuFiles[$menuCode]);
        }
        return [];
    }

    public function getLeftMenuCode(): string
    {
        return $this->leftMenuCode;
    }

    /**
     * @param string $leftMenuCode
     */
    public function setLeftMenu(string $leftMenuCode): void
    {
        $this->leftMenuCode = trim($leftMenuCode);
    }

    /**
     * @return mixed
     */
    public function getPageHeader(): string
    {
        return Yii::$app->view->params['pageHeader'] ?? $this->pageHeader;
    }


    /**
     * @param mixed $pageHeader
     */
    public function setPageHeader($pageHeader): void
    {
        $this->pageHeader = $pageHeader;
    }

     /**
     * @return mixed
     */
    public function getPageFooter(): string
    {
        return Yii::$app->view->params['pageFooter'] ?? $this->pageFooter;
    }


    /**
     * @param mixed $pageFooter
     */
    public function setPageFooter(string $pageFooter): void
    {
        $this->pageFooter = $pageFooter;
    }

    /**
     * @param string $content
     */
    public function addToPageFooter(string $content): void
    {
        $this->pageFooter .= $content;
    }

    /**
     * @return string
     */
    public function getPageHeaderDescription(): string
    {
        return Yii::$app->view->params['pageHeaderDescription']??$this->pageHeaderDescription;
    }


    /**
     * @var string $description
     */
    public function setPageHeaderDescription(string $description): void
    {
        $this->pageHeaderDescription = $description;
    }

    /**
     * @return mixed
     */
    public function getPageIcon():string
    {
        return Yii::$app->view->params['pageIcon'] ?? $this->pageIcon;
    }

    /**
     * @param string $pageIcon
     */
    public function setPageIcon(string $pageIcon): void
    {
        $this->pageIcon = $pageIcon;
    }

    /**
     * Detect the view is partial (URL contains fancybox param)
     * @TODO - Fancybox is deprecated and the param name should be changed to more apprioriate
     * @return bool|null
     */
    public function isPartialView(): ?bool
    {
        return Yii::$app->request->get('fancybox');
    }
}