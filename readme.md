<b>Тестовый компонент битрикса.</b>

1) Написать скрипт\компонент, который будет получать  данные из lenta.ru/rss и сохраняющий их в инфоблоке и highload-е
2) Инфоблок типа Контент, назвать Новости. В инфоблоке будут храниться:



-          Название новости

-          Ссылка

-          Описание

-          Дата публикации новости

-          Св-во: Категория - привязка к справочнику



3) Highload Категории:


<table style="border-collapse:collapse;margin-left:22.1pt">
<tbody><tr><td style="border:1pt solid #cccccc;padding:0.75pt"><p><span style="color:#201f1e;font-family:'helvetica' , sans-serif">Поле</span></p></td><td style="border-color:#cccccc;border-style:solid solid solid none;border-width:1pt 1pt 1pt medium;padding:0.75pt"><p><span style="color:#201f1e;font-family:'helvetica' , sans-serif">Комментарий</span></p></td></tr><tr><td style="border-color:#cccccc;border-style:none solid solid solid;border-width:medium 1pt 1pt 1pt;padding:0.75pt"><p><span style="color:#201f1e;font-family:'helvetica' , sans-serif">ID</span></p></td><td style="border-bottom-color:#cccccc;border-right-color:#cccccc;border-style:none solid solid none;border-width:medium 1pt 1pt medium;padding:0.75pt"><p><span style="color:#201f1e;font-family:'helvetica' , sans-serif">Уникальный&nbsp;ID записи</span></p></td></tr><tr><td style="border-color:#cccccc;border-style:none solid solid solid;border-width:medium 1pt 1pt 1pt;padding:0.75pt"><p><span style="color:#201f1e;font-family:'helvetica' , sans-serif">TITLE</span></p></td><td style="border-bottom-color:#cccccc;border-right-color:#cccccc;border-style:none solid solid none;border-width:medium 1pt 1pt medium;padding:0.75pt"><p><span style="color:#201f1e;font-family:'helvetica' , sans-serif">Название</span></p></td></tr><tr><td style="border-color:#cccccc;border-style:none solid solid solid;border-width:medium 1pt 1pt 1pt;padding:0.75pt"><p><span style="color:#201f1e;font-family:'helvetica' , sans-serif">XML_ID</span></p></td><td style="border-bottom-color:#cccccc;border-right-color:#cccccc;border-style:none solid solid none;border-width:medium 1pt 1pt medium;padding:0.75pt"><p><span style="color:#201f1e;font-family:'helvetica' , sans-serif">Внешний код</span></p></td></tr></tbody>
</table>



4) Реализовать метод, возвращающий список записей по названию категории
5) Описать пример вызова метода