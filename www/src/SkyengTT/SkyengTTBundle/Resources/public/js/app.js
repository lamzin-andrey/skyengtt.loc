var LangMiniTestApp = angular.module('LangMiniTestApp', ['ngRoute', 'LangMiniTestDirectives'])
	.value('UNIVERSAL_ERROR', 'Возникла ошибка, попробуйте еще раз или обновите страницу.')
	.config(function ($routeProvider, $interpolateProvider) {
		$routeProvider.when('/', {templateUrl: '/hello', controller:'HelloScreenController'})
		  .when('/game', {templateUrl: '/game', controller:'GameController'})
		  .when('/win', {templateUrl: '/win', controller:'WinController'})
		  .when('/gameover', {templateUrl: '/gameover', controller:'GOController'});
	  
		$interpolateProvider.startSymbol('[[');
		$interpolateProvider.endSymbol(']]');
	})
  
	.controller('AppController', 
		function ($scope, $rootScope) {
			$rootScope.questionData = $rootScope.questionData ? $rootScope.questionData : {};
			$rootScope.questionData.score = 0;
			$rootScope.username = $rootScope.username ? $rootScope.username : localStorage.username;
			$rootScope.lives = localStorage.lives ? localStorage.lives : 3;
		}
	)
	
	.controller('WinController', 
		function ($scope, $rootScope, $location) {
			if (!$rootScope.questionData.score) {
				$location.path('/');
				return;
			}
			$scope.score = $rootScope.questionData.score ? $rootScope.questionData.score : 0;
			$rootScope.pagePreloader = false;
		}
	)
	
	.controller('GOController', 
		function ($scope, $rootScope, $location) {
			if (!$rootScope.questionData) {
				$location.path('/');
				return;
			}
			$scope.score = $rootScope.questionData.score ? $rootScope.questionData.score : 0;
			$rootScope.pagePreloader = false;
			$rootScope.questionData = false;
		}
	)
	
	.controller('ErrorTooltipController', 
		function ($scope, $rootScope) {
			$scope.closeErrorDlg = function() {
				$rootScope.showErrorMessage = false;
				$rootScope.pagePreloader = false;
			}
		}
	)
	
	.controller('HelloScreenController', 
		function ($scope, $http, $rootScope, $location) {
			$rootScope.questionData = $rootScope.questionData ? $rootScope.questionData : {};
			$rootScope.questionData.score = 0;
			localStorage.removeItem('lives');
			$rootScope.pagePreloader = false;
			$scope.sendUsername = function(evt) {
				if (angular.isUndefined(evt.keyCode) || evt.keyCode == 13) {
				var username = $('#username').val();
					if (username) {
						$rootScope.pagePreloader = true;
						var config = {headers: {'Content-Type': 'application/x-www-form-urlencoded'} };
						$http.post('/appuser/create', $.param( {username:username} ), config)
						
						.success(function(data) {
							localStorage.username = $rootScope.username = username;
							$rootScope.lives = 3;
							$location.path('/game');
						})
						
						.error(function (responseText, status, fn) {
							$rootScope.pagePreloader = false;
							$rootScope.appErrorText = LangMiniTestApp.value('UNIVERSAL_ERROR');
							$rootScope.showErrorMessage = true;
							$rootScope.pagePreloader = true;
							$rootScope.onlyFon = true;
						});
					}
				}
		  }
		}
  )
  /** @description Контроллер теста (по английскому языку)*/
  .controller('GameController', 
    function ($scope, $location, $rootScope, $http) {
		$scope.answer0 = 'Вариант 1';
		$scope.answer1 = 'Вариант 2';
		$scope.answer2 = 'Вариант 3';
		$scope.answer3 = 'Вариант 4';
		
		$scope.answer0Id = 0;
		$scope.answer1Id = 0;
		$scope.answer2Id = 0;
		$scope.answer3Id = 0;
		
		$scope.question   = 'Вопрос';
		$scope.questionId = 0;
		
		if (!$rootScope.questionData.score){
			/** 
			 * @desc Get attribute value from NamedNodeMap
			 * @param {NamedNodeMap} attrs
			 * @param {String} name
			*/
			function _getAttrByName(attrs, name) {
				var r, i;
				for (i in attrs) {
					if (attrs[i].name == name) {
						return attrs[i].nodeValue;
					}
				}
				return r;
			}
			
			/** action send answer  */
			$scope.sendAnswer = function(event) {
				$rootScope.pagePreloader = true;
				var attrs = event.currentTarget.attributes,
					data = {};
				data.answer_id = _getAttrByName(attrs, 'data-id');
				
				$http.post('/answer/add/' + $scope.questionId, $.param(data), config)
				.success(_onSuccessGetQuestion)
				.error(_onFailGetQuestion);
			}
			
			/** 
			 * @desc onSuccessGetQuestion 
			 * @param {Object} data
			*/
			function _onSuccessGetQuestion(data) {
				if (data.success && data.next && data.next.question) {
					$rootScope.questionData = data;
					console.log( $rootScope.questionData );
					$scope.questionId = data.next.question.id;
					$scope.question = data.next.question.word;
					for (var i = 0; i < data.next.answers.length; i++) {
						$scope['answer' + i + 'Id'] = data.next.answers[i].id;
						$scope['answer' + i] = data.next.answers[i].word;
					}
					$rootScope.pagePreloader = false;
				} else if (!data.success) {
					$rootScope.lives--;
					localStorage.setItem('lives', $rootScope.lives);
					if (data.gameover) {
						$location.path('/gameover');
					} else {
						$rootScope.appErrorText = 'Попробуйте еще раз';
						$rootScope.showErrorMessage = true;
						$rootScope.pagePreloader = true;
						$rootScope.onlyFon = true;
					}
				} else {
					$rootScope.questionData.score = data.score;
					$location.path('/win');//TODO Win controller
				}
			}
			/** 
			 * @desc onSuccessGetQuestion 
			 * @param {String} responseText
			 * @param {Number} status
			 * @param {Function} fn
			*/
			function _onFailGetQuestion(responseText, status, fn) {
				$rootScope.pagePreloader = false;
				$location.path('/');
			}
			
			//Construct
			$rootScope.pagePreloader = true;
			var config = {headers: {'Content-Type': 'application/x-www-form-urlencoded'} };
			$http.get('/question/random', config)
			.success(_onSuccessGetQuestion)
			.error(_onFailGetQuestion);
			//end Construct
		} 
      /*$scope.goEnd = function() {
		$location.path('/');
      }*/
    }  
  );
