$(document).ready(function () {
    $('th').click(function () {

        var table = $(this).parents('table').eq(0);
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
        this.asc = !this.asc;
        if (!this.asc) {
            rows = rows.reverse();
        }
        for (var i = 0; i < rows.length; i++) {
            table.append(rows[i]);
        }

        // Remove existing sort icons
        $(this).closest('thead').find('.sort-icon').remove();

        // Add sort icon to clicked th item
        if (this.asc) {
            $(this).append('<span class="sort-icon"><i class="bi bi-caret-up-fill"></i></span>');
        } else {
            $(this).append('<span class="sort-icon"><i class="bi bi-caret-down-fill"></i></span>');
        }
    });
});

function comparer(index) {
    return function (a, b) {
        var valA = getCellValue(a, index);
        var valB = getCellValue(b, index);
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
    };
}

function getCellValue(row, index) {
    return $(row).children('td').eq(index).html();
}