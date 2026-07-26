document.querySelectorAll(".datatable").forEach((table) => {
    const dataTable = new simpleDatatables.DataTable(table);

    function adaptPageDropdown() {
        const selector = dataTable.wrapper.querySelector(".dataTable-selector");
        if (!selector) return;

        selector.parentNode.parentNode.insertBefore(selector, selector.parentNode);
        selector.classList.add("form-select");
    }

    function adaptPagination() {
        dataTable.wrapper
            .querySelectorAll("ul.dataTable-pagination-list")
            .forEach((pagination) => {
                pagination.classList.add("pagination", "pagination-primary");
            });

        dataTable.wrapper
            .querySelectorAll("ul.dataTable-pagination-list li")
            .forEach((li) => li.classList.add("page-item"));

        dataTable.wrapper
            .querySelectorAll("ul.dataTable-pagination-list li a")
            .forEach((a) => a.classList.add("page-link"));
    }

    const refreshPagination = () => adaptPagination();

    dataTable.on("datatable.init", () => {
        adaptPageDropdown();
        refreshPagination();
    });

    dataTable.on("datatable.update", refreshPagination);
    dataTable.on("datatable.sort", refreshPagination);
    dataTable.on("datatable.page", adaptPagination);
});