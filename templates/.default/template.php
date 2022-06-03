<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 *
 */






if (!empty($arResult)) {

    ?>
    <p><b>Последняя добавленная новость:</b></p>

    <?php print_r($arResult);?>

    <br><br><br>
    <hr>
    <br>

    <?php
}



if (CModule::IncludeModule("iblock") && !empty($arParams['CATEGORY'])) {
    $arFilter = array("ACTIVE" => "Y", "PROPERTY_LENTA_CATEGORY" => $arParams['CATEGORY']);
    $rsElement = CIBlockElement::GetList(array(), $arFilter, false, false, array("NAME", "ID", "PROPERTY_LENTA_CATEGORY"));

    ?>


    <p>Записи из категории <b>"<?=$arParams['CATEGORY']?>"</b>:</p>
    <?php


    while ($arElement = $rsElement->GetNext())
    {
        $newsFound = true;
        ?>
        <div><a target="_blank" href="http://srv168062.hoster-test.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arParams['IBLOCK_ID']?>&type=content&lang=ru&ID=<?=$arElement['ID']?>&find_section_section=-1&WF=Y"><?=$arElement['NAME']?></a></div>
        <?php
    }

    if(!$newsFound)
    {
        echo "Пока пусто.";
    }



}
