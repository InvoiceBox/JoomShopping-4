# Описание платежного плагина ИнвойсБокс для CMS Joomla - JoomShoppping 4+

Модуль для интеграции платёжной системы «ИнвойсБокс» и JoomShoppping 3-4. Реализована поддержка платёжного API. Протестировано на CMS Joomla 2.5 - 3.* и компоненте JoomShoppping 3.7 - 4.*.

## Установка плагина

В админ-панели пройдите в "Компоненты" —> "JoomShoppping" —> "Установка и Обновление". Выберите файл "invoicebox_joomshoppping.zip" и нажмите на кнопку "Загрузить".

## Настройка модуля
1. Перейдите в компонент "JoomShoppping" —> "Опции" —> "Способ оплаты";
2. Выберите способ оплаты "InvoiceBox" (ИнвойсБокс) и перейдите во вкладку "Конфигурация" и заполните следующие поля:
    - "Идентификатор магазина"
    - "Региональный код магазина"
    - "Ключ безопасности магазина"
3. Выберите необходимые статусы заказа в опции "Статус заказа для успешных транзакций";
4. Нажмите на кнопку "Сохранить".

### Специфические настройки 

Тестовый режим - включите его для проведения тестовых платежей, при включении этого режима, вы пройдете все шаги в платежном терминале ИнвойсБокс, но деньги с вашей карты списаны не будут.

Изображения URL - ссылка на картинку, которая выводится вместе с платежной системой. 

Цена - дополнительный сбор (в процентах или фикированная сумма) при выборе данного платежного метода.   

Выберите налог - налоговые правила для данного платежного метода.

### Настройка панели ИнвойсБокс:

1. Для настройки панели управления ИнвойсБокс, перейдите по ссылке - https://login.invoicebox.ru/ ;
2. Авторизуйтесь и пройдите в раздел "Мои магазины". "Начало работы" -> "Настройки" -> "Мои магазины";
3. Пройдите по вкладку "Уведомления по протоколу" -> выберите "Тип уведомления" "Оплата/HTTP/Post (HTTP POST запрос с данными оплаты в переменных)"
4. В поле "URL уведомления" укажите:

    `<домен_сайта>/index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_invoicebox&no_lang=1&tmpl=component`

5. Сохраните изменения.