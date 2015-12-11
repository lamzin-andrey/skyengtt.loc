angular.module('LangMiniTestDirectives', [])
	/** прелоадер*/
	.directive('loaderScreen', function($rootScope) {
		return function($scope, element, attrs) {
		  //здесь $scope  - это просто разделяемая с контроллером м представлением область видимости
		  //здесь element - наш div
		  //здесь attrs  - атрибуты дива
		  
		  //мы можем наблюдать за значением аттрибута и реагировать на это
		  $rootScope.pagePreloader = false;
		  $rootScope.$watch('pagePreloader', function(value) {
			  element = $(element[0]);
			  var img = element.find('img');
			  if (value === true) {
				  element.removeClass('hide');
				  element.css('position', 'fixed');
				  element.css('top', '0px');
				  element.css('left', '0px');
				  element.css('opacity', '0.2');
				  element.css('background-color', 'blue');
				  element.width($(window).width());
				  element.height($(window).height());
				  element.find('.loader-place').height( $(window).height() );
				  img.css('top' , ($(window).height()  - img.height() ) / 2 + 'px');
				  if ($rootScope.onlyFon) {
					  img.addClass('hide');
				  }
			  } else {
				  element.addClass('hide');
				  img.removeClass('hide');
				  $rootScope.onlyFon = false;
			  }
		  });
		  
		  $rootScope.$watch('onlyFon', function(value) {
			  element = $(element[0]);
			  var img = element.find('img');
			  if (value === true) {
				img.addClass('hide');
			  } else {
				img.removeClass('hide');
			  }
		  });
		  
		}
	})
	
	/** Сообщение об ошибке*/
	.directive('errorMessage', function($rootScope) {
		return function($scope, element, attrs) {
		  $rootScope.showErrorMessage = false;
		  $rootScope.$watch('showErrorMessage', function(value) {
			  element = $(element[0]);
			  if (value === true) {
				  element.removeClass('hide');
				  element.css('position', 'fixed');
				  element.css('z-index', '5');
				  element.css('top' , ($(window).height()  - element.height() )/ 3);
				  element.css('left' , ($(window).width()  - element.width() )/ 2);
			  } else {
				  element.addClass('hide');
			  }
		  });
		}
	})
	
	;
