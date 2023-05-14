$(document).ready(function () {
    $("input.money").simpleMoneyFormat({
        currencySymbol: "Rp",
        decimalPlaces: 0,
        thousandsSeparator: ".",
    });
});
