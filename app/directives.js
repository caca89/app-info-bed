angular
.module('app')
.directive('includeReplace', includeReplace)
.directive('a', preventClickDirective)
.directive('loadingPage', loadingPage)
.directive('loadingForm', loadingForm)
.directive('infoMessage', infoMessage)
.directive('alertMessage', alertMessage)
.directive('myTable', myTable)
.directive('myDate', myDate)
.directive('myAutoDiagnosa', myAutoDiagnosa)
// .directive('a', bootstrapCollapseDirective)
// .directive('a', navigationDirective)
// .directive('button', layoutToggleDirective)
// .directive('a', layoutToggleDirective)
// .directive('button', collapseMenuTogglerDirective)
// .directive('div', bootstrapCarouselDirective)
// .directive('toggle', bootstrapTooltipsPopoversDirective)
// .directive('tab', bootstrapTabsDirective)
// .directive('button', cardCollapseDirective)
// .directive('myTable', myTable)

function includeReplace() {
  var directive = {
    require: 'ngInclude',
    restrict: 'A',
    link: link
  }
  return directive;

  function link(scope, element, attrs) {
    element.replaceWith(element.children());
  }
}

//Prevent click if href="#"
function preventClickDirective() {
  var directive = {
    restrict: 'E',
    link: link
  }
  return directive;

  function link(scope, element, attrs) {
    if (attrs.href === '#'){
      element.on('click', function(event){
        event.preventDefault();
      });
    }
  }
}

function loadingPage() {
    return {
        templateUrl: 'views/sections/loading-page.html',
        scope: {
            is_loading_page: '=data'
        },
        restrict: 'E',
        controller: function($scope, $rootScope) {           
        }
    }
}

function loadingForm() {
    return {
        templateUrl: 'views/sections/loading-form.html',
        scope: {
            loading_form: '=data'
        },
        restrict: 'E',
        controller: function($scope, $rootScope, $timeout) {         
        }
    }
}

function infoMessage() {
    var directive = {
        templateUrl: 'views/sections/info-message.html',
        scope: {
            info: '=data'
        },
        restrict: 'E',
        link: link
    }
    return directive;
  
    function link(scope, element, attrs) {
       scope.closeMessage = function() {
          scope.info.show = false;
       }
    }
}

function alertMessage() {
    var directive = {
        templateUrl: 'views/sections/alert-message.html',
        scope: {
            info: '=data'
        },
        restrict: 'E',
        link: link
    }
    return directive;
  
    function link(scope, element, attrs) {
       scope.closeAlert = function() {
          scope.alert.show = false;
       }
    }
}

function myTable() {
    var directive = {
        restrict: 'E',
        link: link
    }
    return directive;

    function link(scope, element, attrs) {
      var data_grid = element.find('table'),
      tables = JSON.parse(attrs.tables),
      columns = [],
      th = '';
      $.each(tables.columns, function(key, value) {
          th += '<th style="width: '+value.width+'">'+value.title+'</th>';
          columns.push({"data": key, "name": value.name});
      })
      data_grid.find('thead tr').html(th);
      data_grid.DataTable({
          "serverSide": true,
          "ajax": {
              url: attrs.url,
              type: 'GET',
              headers: {
                  "User-id": localStorage.getItem('mca_user_id'),
                  "Api-Key": localStorage.getItem('mca_api_key'),
                  "Secret-Key": secretKey                    
              }
          },
          "columns": columns,
          "columnDefs": [ { orderable: false, targets: [tables.disable_sorting] } ],
          "order": [[ tables.default_sort_col, tables.default_sort_order ]]
      });

    }
}

function myDate() {
  var directive = {
    restrict: 'A',
    link: link
  }
  return directive;

  function link(scope, element, attrs) {
    var options       = {dateFormat: 'dd-mm-yy'};
    var change_month  = element.attr('data-change_month');
    var change_year   = element.attr('data-change_year');
    var min_date      = element.attr('data-min_date');
    var max_date      = element.attr('data-max_date');
    if(typeof change_month !== 'undefined') { options.changeMonth = true; }
    if(typeof change_year !== 'undefined') { options.changeYear = true; }
    if(typeof min_date !== 'undefined') { options.minDate = min_date; }
    if(typeof change_year !== 'undefined') { options.maxDate = max_date; }
    element.datepicker(options);
  }
}

function myAutoDiagnosa() {
  var directive = {
    restrict: 'A',
    link: link
  }
  return directive;

  function link(scope, element, attrs) {
    element.autocomplete({
      source: baseUrl+"/api/transaction/claim_provider/get_diagnosa",
      minLength: 2,
      select: function( event, ui ) {
        var diagnosa = {icd_code: ui.item.id, label: ui.item.label};
        scope.setDiagnosa(diagnosa);
        $('#diagnosa').val("");
        return false;
      }
    });
  }
}


// //Bootstrap Collapse
// function bootstrapCollapseDirective() {
//   var directive = {
//     restrict: 'E',
//     link: link
//   }
//   return directive;

//   function link(scope, element, attrs) {
//     if (attrs.toggle=='collapse'){
//       element.attr('href','javascript;;').attr('data-target',attrs.href.replace('index.html',''));
//     }
//   }
// }

// /**
// * @desc Genesis main navigation - Siedebar menu
// * @example <li class="nav-item nav-dropdown"></li>
// */
// function navigationDirective() {
//   var directive = {
//     restrict: 'E',
//     link: link
//   }
//   return directive;

//   function link(scope, element, attrs) {
//     if(element.hasClass('nav-dropdown-toggle') && angular.element('body').width() > 782) {
//       element.on('click', function(){
//         if(!angular.element('body').hasClass('compact-nav')) {
//           element.parent().toggleClass('open').find('.open').removeClass('open');
//         }
//       });
//     } else if (element.hasClass('nav-dropdown-toggle') && angular.element('body').width() < 783) {
//       element.on('click', function(){
//         element.parent().toggleClass('open').find('.open').removeClass('open');
//       });
//     }
//   }
// }

// //Dynamic resize .sidebar-nav
// sidebarNavDynamicResizeDirective.$inject = ['$window', '$timeout'];
// function sidebarNavDynamicResizeDirective($window, $timeout) {
//   var directive = {
//     restrict: 'E',
//     link: link
//   }
//   return directive;

//   function link(scope, element, attrs) {

//     if (element.hasClass('sidebar-nav') && angular.element('body').hasClass('fixed-nav')) {
//       var bodyHeight = angular.element(window).height();
//       scope.$watch(function(){
//         var headerHeight = angular.element('header').outerHeight();

//         if (angular.element('body').hasClass('sidebar-off-canvas')) {
//           element.css('height', bodyHeight);
//         } else {
//           element.css('height', bodyHeight - headerHeight);
//         }
//       })

//       angular.element($window).bind('resize', function(){
//         var bodyHeight = angular.element(window).height();
//         var headerHeight = angular.element('header').outerHeight();
//         var sidebarHeaderHeight = angular.element('.sidebar-header').outerHeight();
//         var sidebarFooterHeight = angular.element('.sidebar-footer').outerHeight();

//         if (angular.element('body').hasClass('sidebar-off-canvas')) {
//           element.css('height', bodyHeight - sidebarHeaderHeight - sidebarFooterHeight);
//         } else {
//           element.css('height', bodyHeight - headerHeight - sidebarHeaderHeight - sidebarFooterHeight);
//         }
//       });
//     }
//   }
// }

// //LayoutToggle
// layoutToggleDirective.$inject = ['$interval'];
// function layoutToggleDirective($interval) {
//   var directive = {
//     restrict: 'E',
//     link: link
//   }
//   return directive;

//   function link(scope, element, attrs) {
//     element.on('click', function(){

//       if (element.hasClass('sidebar-toggler')) {
//         angular.element('body').toggleClass('sidebar-hidden');
//       }

//       if (element.hasClass('aside-menu-toggler')) {
//         angular.element('body').toggleClass('aside-menu-hidden');
//       }
//     });
//   }
// }

// //Collapse menu toggler
// function collapseMenuTogglerDirective() {
//   var directive = {
//     restrict: 'E',
//     link: link
//   }
//   return directive;

//   function link(scope, element, attrs) {
//     element.on('click', function(){
//       if (element.hasClass('navbar-toggler') && !element.hasClass('layout-toggler')) {
//         angular.element('body').toggleClass('sidebar-mobile-show')
//       }
//     })
//   }
// }

// //Bootstrap Carousel
// function bootstrapCarouselDirective() {
//   var directive = {
//     restrict: 'E',
//     link: link
//   }
//   return directive;

//   function link(scope, element, attrs) {
//     if (attrs.ride=='carousel'){
//       element.find('a').each(function(){
//         $(this).attr('data-target',$(this).attr('href').replace('index.html','')).attr('href','javascript;;')
//       });
//     }
//   }
// }

// //Bootstrap Tooltips & Popovers
// function bootstrapTooltipsPopoversDirective() {
//   var directive = {
//     restrict: 'A',
//     link: link
//   }
//   return directive;

//   function link(scope, element, attrs) {
//     if (attrs.toggle=='tooltip'){
//       angular.element(element).tooltip();
//     }
//     if (attrs.toggle=='popover'){
//       angular.element(element).popover();
//     }
//   }
// }

// //Bootstrap Tabs
// function bootstrapTabsDirective() {
//   var directive = {
//     restrict: 'A',
//     link: link
//   }
//   return directive;

//   function link(scope, element, attrs) {
//     element.click(function(e) {
//       e.preventDefault();
//       angular.element(element).tab('show');
//     });
//   }
// }
