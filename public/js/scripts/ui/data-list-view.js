/*=========================================================================================
    File Name: data-list-view.js
    Description: List View
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(document).ready(function () {
    "use strict";
    // init list view datatable
    var dataListView = $(".data-list-view").DataTable({
        responsive: true,
        columnDefs: [
            {
                orderable: true,
                targets: 0
            }
        ],
        dom:
            '<"top"<"actions action-btns"B><"action-filters"lf>><"clear">rt<"bottom"<"actions">p>',
        oLanguage: {
            sProcessing: "Traitement en cours...",
            sSearch: "&nbsp; ",
            sLengthMenu: "_MENU_ ",
            sInfo: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            sInfoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            sInfoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            sInfoPostFix: "",
            sLoadingRecords: "Chargement en cours...",
            sZeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
            sEmptyTable: "Aucune donnée disponible dans le tableau",
            oPaginate: {
                sFirst: "Premier",
                sPrevious: "Pr&eacute;c&eacute;dent",
                sNext: "Suivant",
                sLast: "Dernier"
            },
            oAria: {
                sSortAscending: ": activer pour trier la colonne par ordre croissant",
                sSortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        },
        aLengthMenu: [[10, 50, 100, 500, 1000], [10, 50, 100, 500, 1000]],
        select: {
            style: "multi"
        },
        order: [[0, "desc"]],
        bInfo: false,
        paging: false,
        ordering: false,
        searching: false,
        info: false,
        pageLength: 50,
        buttons: [],
        initComplete: function (settings, json) {
            $(".dt-buttons .btn").removeClass("btn-secondary");
            $(".id-add-btn").detach().appendTo(".action-btns");
        }
    });


    // To append actions dropdown before add new button
    var actionDropdown = $(".actions-dropodown");
    actionDropdown.insertBefore($(".top .actions .dt-buttons"));


    // Scrollbar
    if ($(".data-items").length > 0) {
        new PerfectScrollbar(".data-items", {wheelPropagation: false})
    }

    // Close sidebar
    $(".hide-data-sidebar, .cancel-data-btn, .overlay-bg").on("click", function () {
        $(".add-new-data").removeClass("show");
        $(".overlay-bg").removeClass("show");
        $("#data-name, #data-price").val("");
        $("#data-category, #data-status").prop("selectedIndex", 0)
    });


});
