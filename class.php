<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class LentaNewsComponent extends CBitrixComponent {

    private $news;



    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function checkModules() {
        if (!Loader::includeModule('iblock') || !Loader::includeModule('highloadblock'))
        {
            throw new \Exception(Loc::getMessage('MODULE_NOT_INSTALLED'));
        }

        return true;
    }





    /**
     * Метод для получения экземпляра класса
     * @param $arParams
     * @return mixed
     */
    private function GetEntityDataClass($HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlBlock = HLBT::getById($HlBlockId)->fetch();
        $entity = HLBT::compileEntity($hlBlock);
        $entityDataClass = $entity->getDataClass();

        return $entityDataClass;
    }




    /**
     * Добавляем новые категории
     * @return bool
     */
    private function addCategories() {

        $itemsFromRss=$this->news->channel->item;

        // Берем все уникальные категории с ленты
        foreach($itemsFromRss as $item)
        {
            $categoriesFromRss[''.$item->category.'']=(string) $item->category;
        }

        // Создадим ссылку на класс для работы с нужным HL блоком
        $entityDataClass = $this->GetEntityDataClass($this->arParams['HL_ID']);

        // Берем категории, которые уже есть на сайте
        $rsData = $entityDataClass::getList(
            [
                'select' => array('*')
            ]
        );

        // Итерация по уже существующим категориям сайта
        while($el = $rsData->fetch()){

            // Сравниваем категории сайта с категориями с ленты. Берем только новые
            if(in_array($el['UF_NAME'], $categoriesFromRss))
            {
                unset($categoriesFromRss[$el['UF_NAME']]);
            }

        }

        // Если есть новые категории, то добавим их в HL блок
        if(array_filter($categoriesFromRss))
        {

            foreach($categoriesFromRss as $categoryToAdd)
            {
                $data[]=
                [
                    'UF_NAME' => $categoryToAdd,
                    'UF_XML_ID' => $categoryToAdd,
                    'UF_COMMENT' => ""
                ];
            }

            $result = $entityDataClass::addMulti($data);

        }

        return $result;
    }









    /**
     * Добавляем на сайт новые элементы
     * @return bool
     */
    private function addElements() {

        $itemsFromRss=$this->news->channel->item;

        // Берем все уникальные новости с ленты
        foreach($itemsFromRss as $item)
        {
            $elementsFromRss[''.$item->guid.'']=(array) $item;
        }

        $elementsFromRss = array_reverse($elementsFromRss); //    Начинаем добавление с самых старых новостей

        // Берем все актуальные новости, которые уже на сайте
        $dbItems = \Bitrix\Iblock\ElementTable::getList(
            [
            'order' => ['SORT' => 'ASC'],
            'select' => ['ID', 'NAME', 'IBLOCK_ID', 'SORT', 'XML_ID'],
            'filter' => ['IBLOCK_ID' => $this->arParams['IBLOCK_ID']],
            'limit' => 200
            ]
        );


        // Итерация по элементам сайта
        while($el = $dbItems->fetch())
        {
            // Сравниваем элементы сайта с новостями с ленты. Берем только новые
            if(array_key_exists($el['XML_ID'], $elementsFromRss))
            {
                unset($elementsFromRss[$el['XML_ID']]);
            }
        }

        // Если есть свежие новости, то добавим новый элемент на сайт
        if(array_filter($elementsFromRss))
        {
            $el = new CIBlockElement;

            foreach($elementsFromRss as $elementToAdd)
            {

                $data=
                    [
                        'NAME' => $elementToAdd['title'],
                        'XML_ID' => $elementToAdd['guid'],
                        'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                        'PROPERTY_VALUES' =>
                            [
                                'LENTA_LINK' => $elementToAdd['link'],
                                'LENTA_CATEGORY' => $elementToAdd['category'],
                                'LENTA_DATE' => date("d.m.Y H:i:s", strtotime($elementToAdd['pubDate']))
                            ]
                    ];

                    if(!$result = $el->Add($data))
                    {
                        throw new \Exception(Loc::getMessage('ERROR_ADDING_ELEMENT'));
                    }

                    unset($elementToAdd['enclosure'], $elementToAdd['description']);
                    $this->lastAddedElement = $elementToAdd; // Для статистики

                    // Добавляем сразу все новости или по одной
                    if($this->arParams['PER_ONE']=='Y')
                    {
                        break;
                    }


            }

        }

        return $result;

    }







    /**
     * Забираем новости с источника
     * @throws Exception
     * @return object
     */
    private function getRssContent() {

        $xml=file_get_contents($this->arParams['URL']);

        if ($xml)
        {
            $content = simplexml_load_string($xml);
        }
        else
        {
            throw new \Exception(Loc::getMessage('ERROR_DOWNLOADING'));
        }
        return $content;
    }



    /**
     * Забираем новости с источника
     */
    private function addNews() {

        $this->news=$this->getRssContent(); // Полуаем массив с новостями

        $this->addCategories(); //  Категории
        $this->addElements();   // Элементы

    }



    /**
     * Точка входа в компонент
     */
    public function executeComponent() {

        $this->checkModules();

        $this->addNews(); // Парсим и добавляем новости с категориями на сайт

        $this->arResult = $this->lastAddedElement;

        $this->includeComponentTemplate();
    }
}
