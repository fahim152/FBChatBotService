/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/custom/table.js":
/*!**************************************!*\
  !*** ./resources/js/custom/table.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function custom_datatable(grid, table, ajax_url, unsortable_fields) {
  $(".date-picker").datepicker({
    autoclose: !0
  });
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  grid.init({
    src: table,
    onSuccess: function onSuccess(grid, response) {},
    onError: function onError(grid) {},
    onDataLoad: function onDataLoad(grid) {},
    loadingMessage: 'Loading...',
    dataTable: {
      "bStateSave": false,
      "lengthMenu": [[15, 30, 50, 100, 200, -1], [15, 30, 50, 100, 200, "All"]],
      "pageLength": 15,
      "ajax": {
        "url": ajax_url
      },
      // "order": [
      //     [0, "asc"]
      // ],
      "columnDefs": [{
        className: "no-sort",
        "targets": unsortable_fields
        /*[0,3,4,5],*/

      }],
      buttons: [{
        extend: 'print',
        className: 'btn default'
      }, {
        extend: 'copy',
        className: 'btn default'
      }, {
        extend: 'pdf',
        className: 'btn default'
      }, {
        extend: 'excel',
        className: 'btn default'
      }, {
        extend: 'csv',
        className: 'btn default'
      }, {
        text: 'Reload',
        className: 'btn default',
        action: function action(e, dt, node, config) {
          dt.ajax.reload();
          alert('Datatable reloaded!');
        }
      }]
    }
  }); // handle group actionsubmit button click

  grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
    e.preventDefault();
    var action = $(".table-group-action-input", grid.getTableWrapper());

    if (action.val() !== "" && grid.getSelectedRowsCount() > 0) {
      grid.setAjaxParam("customActionType", "group_action");
      grid.setAjaxParam("customActionName", action.val());
      grid.setAjaxParam("id", grid.getSelectedRows());
      grid.getDataTable().ajax.reload();
      grid.clearAjaxParams();
    } else if (action.val() === "") {
      App.alert({
        type: 'danger',
        icon: 'warning',
        message: 'Please select an action',
        container: grid.getTableWrapper(),
        place: 'prepend'
      });
    } else if (grid.getSelectedRowsCount() === 0) {
      App.alert({
        type: 'danger',
        icon: 'warning',
        message: 'No record selected',
        container: grid.getTableWrapper(),
        place: 'prepend'
      });
    }
  });
  grid.getTableWrapper().on('click', '.table-row-delete', function (e) {
    e.preventDefault();
    var record_id = $(this).data('id');

    if (record_id) {
      var action = confirm("Do you want to delete this?");

      if (action) {
        grid.setAjaxParam("actionType", "delete_action");
        grid.setAjaxParam("record_id", record_id);
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
      }
    }
  });
  $('#datatable_ajax_tools > li > a.tool-action').on('click', function () {
    var action = $(this).attr('data-action');
    grid.getDataTable().button(action).trigger();
  });
}

function generateArray(str) {
  var arr = str.split(",").map(Number);
  return arr;
}
/* This function basically updates the status ["is_seen"]
 * which manages the  "new" tag for specific users in datatable
 */


$("#table_ajax_datatable").on('click', '.btn-detail', function () {
  var data_id = $(this).data('id');
  var data_seen = $(this).data('is-seen');
  var data_url = $(this).data('url');
  var data_url_prefix = $(this).data('url-prefix');
  var url_prefix_arr = ['certificates', 'permissions'];

  if (jQuery.inArray(data_url_prefix, url_prefix_arr) == -1) {
    App.alert({
      type: 'danger',
      icon: 'warning',
      message: "Wrong URL Prefix!",
      container: grid.getTableWrapper(),
      place: 'prepend'
    });
    return;
  }

  $.ajax({
    url: '/' + data_url_prefix + '/seen',
    data: {
      'data_id': data_id,
      'is_seen_val': data_seen
    },
    method: 'POST'
  }).done(function (response) {
    if (typeof response.success != 'undefined') {
      if (response.success) {
        $('#new-notifier-' + data_id).remove();
        window.location.href = data_url;
      }
    }
  }).fail(function (response) {
    var message = "Something Went Wrong!";

    if (typeof response.message != 'undefined') {
      if (response.message.length > 0) {
        message = response.message;
      }
    }

    App.alert({
      type: 'danger',
      icon: 'warning',
      message: message,
      container: grid.getTableWrapper(),
      place: 'prepend'
    });
  });
});
$("#table_ajax_datatable").on("click", '.btn-print', function () {
  $(this).hide();
});
$(document).ready(function () {
  if ($("#table_ajax_datatable").length && $("#table_ajax_url").length) {
    var table = $("#table_ajax_datatable");
    var ajax_url = $("#table_ajax_url").val();
    var unsortable_fields = generateArray($("#table_ajax_unsortable").val());
    window.grid = new Datatable();
    custom_datatable(window.grid, table, ajax_url, unsortable_fields);
  }
});

/***/ }),

/***/ "./resources/js/global/datatables/datatables.bootstrap.js":
/*!****************************************************************!*\
  !*** ./resources/js/global/datatables/datatables.bootstrap.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* Set the defaults for DataTables initialisation */
$.extend(true, $.fn.dataTable.defaults, {
  "dom": "<'row'<'col-md-6 col-sm-6'l><'col-md-6 col-sm-6'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-5'i><'col-md-7 col-sm-7'p>>",
  // default layout with horizobtal scrollable datatable
  //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // datatable layout without  horizobtal scroll(used when bootstrap dropdowns used in the datatable cells)
  "language": {
    "lengthMenu": " _MENU_ records ",
    "paginate": {
      "previous": 'Prev',
      "next": 'Next',
      "page": "Page",
      "pageOf": "of"
    }
  },
  "pagingType": "bootstrap_number"
});
/* Default class modification */

$.extend($.fn.dataTableExt.oStdClasses, {
  "sWrapper": "dataTables_wrapper",
  "sFilterInput": "form-control input-sm input-small input-inline",
  "sLengthSelect": "form-control input-sm input-xsmall input-inline"
}); // In 1.10 we use the pagination renderers to draw the Bootstrap paging,
// rather than  custom plug-in

$.fn.dataTable.defaults.renderer = 'bootstrap';

$.fn.dataTable.ext.renderer.pageButton.bootstrap = function (settings, host, idx, buttons, page, pages) {
  var api = new $.fn.dataTable.Api(settings);
  var classes = settings.oClasses;
  var lang = settings.oLanguage.oPaginate;
  var btnDisplay, btnClass;

  var attach = function attach(container, buttons) {
    var i, ien, node, button;

    var clickHandler = function clickHandler(e) {
      e.preventDefault();

      if (e.data.action !== 'ellipsis') {
        api.page(e.data.action).draw(false);
      }
    };

    for (i = 0, ien = buttons.length; i < ien; i++) {
      button = buttons[i];

      if ($.isArray(button)) {
        attach(container, button);
      } else {
        btnDisplay = '';
        btnClass = '';

        switch (button) {
          case 'ellipsis':
            btnDisplay = '&hellip;';
            btnClass = 'disabled';
            break;

          case 'first':
            btnDisplay = lang.sFirst;
            btnClass = button + (page > 0 ? '' : ' disabled');
            break;

          case 'previous':
            btnDisplay = lang.sPrevious;
            btnClass = button + (page > 0 ? '' : ' disabled');
            break;

          case 'next':
            btnDisplay = lang.sNext;
            btnClass = button + (page < pages - 1 ? '' : ' disabled');
            break;

          case 'last':
            btnDisplay = lang.sLast;
            btnClass = button + (page < pages - 1 ? '' : ' disabled');
            break;

          default:
            btnDisplay = button + 1;
            btnClass = page === button ? 'active' : '';
            break;
        }

        if (btnDisplay) {
          node = $('<li>', {
            'class': classes.sPageButton + ' ' + btnClass,
            'aria-controls': settings.sTableId,
            'tabindex': settings.iTabIndex,
            'id': idx === 0 && typeof button === 'string' ? settings.sTableId + '_' + button : null
          }).append($('<a>', {
            'href': '#'
          }).html(btnDisplay)).appendTo(container);

          settings.oApi._fnBindAction(node, {
            action: button
          }, clickHandler);
        }
      }
    }
  };

  attach($(host).empty().html('<ul class="pagination"/>').children('ul'), buttons);
};
/***
Custom Pagination
***/

/* API method to get paging information */


$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
  return {
    "iStart": oSettings._iDisplayStart,
    "iEnd": oSettings.fnDisplayEnd(),
    "iLength": oSettings._iDisplayLength,
    "iTotal": oSettings.fnRecordsTotal(),
    "iFilteredTotal": oSettings.fnRecordsDisplay(),
    "iPage": oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
    "iTotalPages": oSettings._iDisplayLength === -1 ? 0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
  };
};
/* Bootstrap style full number pagination control */


$.extend($.fn.dataTableExt.oPagination, {
  "bootstrap_full_number": {
    "fnInit": function fnInit(oSettings, nPaging, fnDraw) {
      var oLang = oSettings.oLanguage.oPaginate;

      var fnClickHandler = function fnClickHandler(e) {
        e.preventDefault();

        if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
          fnDraw(oSettings);
        }
      };

      $(nPaging).append('<ul class="pagination">' + '<li class="prev disabled"><a href="#" title="' + oLang.sFirst + '"><i class="fa fa-angle-double-left"></i></a></li>' + '<li class="prev disabled"><a href="#" title="' + oLang.sPrevious + '"><i class="fa fa-angle-left"></i></a></li>' + '<li class="next disabled"><a href="#" title="' + oLang.sNext + '"><i class="fa fa-angle-right"></i></a></li>' + '<li class="next disabled"><a href="#" title="' + oLang.sLast + '"><i class="fa fa-angle-double-right"></i></a></li>' + '</ul>');
      var els = $('a', nPaging);
      $(els[0]).bind('click.DT', {
        action: "first"
      }, fnClickHandler);
      $(els[1]).bind('click.DT', {
        action: "previous"
      }, fnClickHandler);
      $(els[2]).bind('click.DT', {
        action: "next"
      }, fnClickHandler);
      $(els[3]).bind('click.DT', {
        action: "last"
      }, fnClickHandler);
    },
    "fnUpdate": function fnUpdate(oSettings, fnDraw) {
      var iListLength = 5;
      var oPaging = oSettings.oInstance.fnPagingInfo();
      var an = oSettings.aanFeatures.p;
      var i,
          j,
          sClass,
          iStart,
          iEnd,
          iHalf = Math.floor(iListLength / 2);

      if (oPaging.iTotalPages < iListLength) {
        iStart = 1;
        iEnd = oPaging.iTotalPages;
      } else if (oPaging.iPage <= iHalf) {
        iStart = 1;
        iEnd = iListLength;
      } else if (oPaging.iPage >= oPaging.iTotalPages - iHalf) {
        iStart = oPaging.iTotalPages - iListLength + 1;
        iEnd = oPaging.iTotalPages;
      } else {
        iStart = oPaging.iPage - iHalf + 1;
        iEnd = iStart + iListLength - 1;
      }

      for (i = 0, iLen = an.length; i < iLen; i++) {
        if (oPaging.iTotalPages <= 0) {
          $('.pagination', an[i]).css('visibility', 'hidden');
        } else {
          $('.pagination', an[i]).css('visibility', 'visible');
        } // Remove the middle elements


        $('li:gt(1)', an[i]).filter(':not(.next)').remove(); // Add the new list items and their event handlers

        for (j = iStart; j <= iEnd; j++) {
          sClass = j == oPaging.iPage + 1 ? 'class="active"' : '';
          $('<li ' + sClass + '><a href="#">' + j + '</a></li>').insertBefore($('li.next:first', an[i])[0]).bind('click', function (e) {
            e.preventDefault();
            oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
            fnDraw(oSettings);
          });
        } // Add / remove disabled classes from the static elements


        if (oPaging.iPage === 0) {
          $('li.prev', an[i]).addClass('disabled');
        } else {
          $('li.prev', an[i]).removeClass('disabled');
        }

        if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
          $('li.next', an[i]).addClass('disabled');
        } else {
          $('li.next', an[i]).removeClass('disabled');
        }
      }
    }
  }
});
$.extend($.fn.dataTableExt.oPagination, {
  "bootstrap_number": {
    "fnInit": function fnInit(oSettings, nPaging, fnDraw) {
      var oLang = oSettings.oLanguage.oPaginate;

      var fnClickHandler = function fnClickHandler(e) {
        e.preventDefault();

        if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
          fnDraw(oSettings);
        }
      };

      $(nPaging).append('<ul class="pagination">' + '<li class="prev disabled"><a href="#" title="' + oLang.sPrevious + '"><i class="fa fa-angle-left"></i></a></li>' + '<li class="next disabled"><a href="#" title="' + oLang.sNext + '"><i class="fa fa-angle-right"></i></a></li>' + '</ul>');
      var els = $('a', nPaging);
      $(els[0]).bind('click.DT', {
        action: "previous"
      }, fnClickHandler);
      $(els[1]).bind('click.DT', {
        action: "next"
      }, fnClickHandler);
    },
    "fnUpdate": function fnUpdate(oSettings, fnDraw) {
      var iListLength = 5;
      var oPaging = oSettings.oInstance.fnPagingInfo();
      var an = oSettings.aanFeatures.p;
      var i,
          j,
          sClass,
          iStart,
          iEnd,
          iHalf = Math.floor(iListLength / 2);

      if (oPaging.iTotalPages < iListLength) {
        iStart = 1;
        iEnd = oPaging.iTotalPages;
      } else if (oPaging.iPage <= iHalf) {
        iStart = 1;
        iEnd = iListLength;
      } else if (oPaging.iPage >= oPaging.iTotalPages - iHalf) {
        iStart = oPaging.iTotalPages - iListLength + 1;
        iEnd = oPaging.iTotalPages;
      } else {
        iStart = oPaging.iPage - iHalf + 1;
        iEnd = iStart + iListLength - 1;
      }

      for (i = 0, iLen = an.length; i < iLen; i++) {
        if (oPaging.iTotalPages <= 0) {
          $('.pagination', an[i]).css('visibility', 'hidden');
        } else {
          $('.pagination', an[i]).css('visibility', 'visible');
        } // Remove the middle elements


        $('li:gt(0)', an[i]).filter(':not(.next)').remove(); // Add the new list items and their event handlers

        for (j = iStart; j <= iEnd; j++) {
          sClass = j == oPaging.iPage + 1 ? 'class="active"' : '';
          $('<li ' + sClass + '><a href="#">' + j + '</a></li>').insertBefore($('li.next:first', an[i])[0]).bind('click', function (e) {
            e.preventDefault();
            oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
            fnDraw(oSettings);
          });
        } // Add / remove disabled classes from the static elements


        if (oPaging.iPage === 0) {
          $('li.prev', an[i]).addClass('disabled');
        } else {
          $('li.prev', an[i]).removeClass('disabled');
        }

        if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
          $('li.next', an[i]).addClass('disabled');
        } else {
          $('li.next', an[i]).removeClass('disabled');
        }
      }
    }
  }
});
/* Bootstrap style full number pagination control */

$.extend($.fn.dataTableExt.oPagination, {
  "bootstrap_extended": {
    "fnInit": function fnInit(oSettings, nPaging, fnDraw) {
      var oLang = oSettings.oLanguage.oPaginate;
      var oPaging = oSettings.oInstance.fnPagingInfo();

      var fnClickHandler = function fnClickHandler(e) {
        e.preventDefault();

        if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
          fnDraw(oSettings);
        }
      };

      $(nPaging).append('<div class="pagination-panel"> ' + (oLang.page ? oLang.page : '') + ' ' + '<a href="#" class="btn btn-sm default prev disabled"><i class="fa fa-angle-left"></i></a>' + '<input type="text" class="pagination-panel-input form-control input-sm input-inline input-mini" maxlenght="5" style="text-align:center; margin: 0 5px;">' + '<a href="#" class="btn btn-sm default next disabled"><i class="fa fa-angle-right"></i></a> ' + (oLang.pageOf ? oLang.pageOf + ' <span class="pagination-panel-total"></span>' : '') + '</div>');
      var els = $('a', nPaging);
      $(els[0]).bind('click.DT', {
        action: "previous"
      }, fnClickHandler);
      $(els[1]).bind('click.DT', {
        action: "next"
      }, fnClickHandler);
      $('.pagination-panel-input', nPaging).bind('change.DT', function (e) {
        var oPaging = oSettings.oInstance.fnPagingInfo();
        e.preventDefault();
        var page = parseInt($(this).val());

        if (page > 0 && page <= oPaging.iTotalPages) {
          if (oSettings.oApi._fnPageChange(oSettings, page - 1)) {
            fnDraw(oSettings);
          }
        } else {
          $(this).val(oPaging.iPage + 1);
        }
      });
      $('.pagination-panel-input', nPaging).bind('keypress.DT', function (e) {
        var oPaging = oSettings.oInstance.fnPagingInfo();

        if (e.which == 13) {
          var page = parseInt($(this).val());

          if (page > 0 && page <= oSettings.oInstance.fnPagingInfo().iTotalPages) {
            if (oSettings.oApi._fnPageChange(oSettings, page - 1)) {
              fnDraw(oSettings);
            }
          } else {
            $(this).val(oPaging.iPage + 1);
          }

          e.preventDefault();
        }
      });
    },
    "fnUpdate": function fnUpdate(oSettings, fnDraw) {
      var iListLength = 5;
      var oPaging = oSettings.oInstance.fnPagingInfo();
      var an = oSettings.aanFeatures.p;
      var i,
          j,
          sClass,
          iStart,
          iEnd,
          iHalf = Math.floor(iListLength / 2);

      if (oPaging.iTotalPages < iListLength) {
        iStart = 1;
        iEnd = oPaging.iTotalPages;
      } else if (oPaging.iPage <= iHalf) {
        iStart = 1;
        iEnd = iListLength;
      } else if (oPaging.iPage >= oPaging.iTotalPages - iHalf) {
        iStart = oPaging.iTotalPages - iListLength + 1;
        iEnd = oPaging.iTotalPages;
      } else {
        iStart = oPaging.iPage - iHalf + 1;
        iEnd = iStart + iListLength - 1;
      }

      for (i = 0, iLen = an.length; i < iLen; i++) {
        var wrapper = $(an[i]).parents(".dataTables_wrapper");

        if (oPaging.iTotal <= 0) {
          $('.dataTables_paginate, .dataTables_length', wrapper).hide();
        } else {
          $('.dataTables_paginate, .dataTables_length', wrapper).show();
        }

        if (oPaging.iTotalPages <= 0) {
          $('.dataTables_paginate, .dataTables_length .seperator', wrapper).hide();
        } else {
          $('.dataTables_paginate, .dataTables_length .seperator', wrapper).show();
        }

        $('.pagination-panel-total', an[i]).html(oPaging.iTotalPages);
        $('.pagination-panel-input', an[i]).val(oPaging.iPage + 1); // Remove the middle elements

        $('li:gt(1)', an[i]).filter(':not(.next)').remove(); // Add the new list items and their event handlers

        for (j = iStart; j <= iEnd; j++) {
          sClass = j == oPaging.iPage + 1 ? 'class="active"' : '';
          $('<li ' + sClass + '><a href="#">' + j + '</a></li>').insertBefore($('li.next:first', an[i])[0]).bind('click', function (e) {
            e.preventDefault();
            oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
            fnDraw(oSettings);
          });
        } // Add / remove disabled classes from the static elements


        if (oPaging.iPage === 0) {
          $('a.prev', an[i]).addClass('disabled');
        } else {
          $('a.prev', an[i]).removeClass('disabled');
        }

        if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
          $('a.next', an[i]).addClass('disabled');
        } else {
          $('a.next', an[i]).removeClass('disabled');
        }
      }
    }
  }
});

/***/ }),

/***/ "./resources/js/table.js":
/*!*******************************!*\
  !*** ./resources/js/table.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// require ('./global/bootstrap-datepicker');
__webpack_require__(/*! ./global/datatables/datatables.bootstrap */ "./resources/js/global/datatables/datatables.bootstrap.js");

__webpack_require__(/*! ./custom/table */ "./resources/js/custom/table.js");

/***/ }),

/***/ 3:
/*!*************************************!*\
  !*** multi ./resources/js/table.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\xampp-7.3.2\htdocs\scratch-booster\resources\js\table.js */"./resources/js/table.js");


/***/ })

/******/ });