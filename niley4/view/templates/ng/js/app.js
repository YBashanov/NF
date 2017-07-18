'use strict';

/**
 * Приложение
 * */
var App = angular.module('niley4', ['ngRoute'], function($httpProvider){
        // Используем x-www-form-urlencoded Content-Type
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

        // Переопределяем дефолтный transformRequest в $http-сервисе
        $httpProvider.defaults.transformRequest = [function(data)
        {
            /**
             * рабочая лошадка; преобразует объект в x-www-form-urlencoded строку.
             * @param {Object} obj
             * @return {String}
             */
            var param = function(obj)
            {
                var query = '';
                var name, value, fullSubName, subValue, innerObj, i;

                for(name in obj)
                {
                    value = obj[name];

                    if(value instanceof Array)
                    {
                        for(i=0; i<value.length; ++i)
                        {
                            subValue = value[i];
                            fullSubName = name + '[' + i + ']';
                            innerObj = {};
                            innerObj[fullSubName] = subValue;
                            query += param(innerObj) + '&';
                        }
                    }
                    else if(value instanceof Object)
                    {
                        for(var subName in value)
                        {
                            subValue = value[subName];
                            fullSubName = name + '[' + subName + ']';
                            innerObj = {};
                            innerObj[fullSubName] = subValue;
                            query += param(innerObj) + '&';
                        }
                    }
                    else if(value !== undefined && value !== null)
                    {
                        query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
                    }
                }

                return query.length ? query.substr(0, query.length - 1) : query;
            };

            return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
        }];
})
    .config(['$routeProvider', '$locationProvider', configRoute])


/**
 * Настройки роутинга
 * */
function configRoute($routeProvider, $locationProvider)
{
    $routeProvider
        .when('/', {
            templateUrl: 'view/templates/ng/html/main/main.html',
            controller: 'Main'
            //resolve: {
            //    factory: scopeInit
            //}
        })
        .when('/games/spy', {
            templateUrl: 'view/templates/ng/html/games/spy.html',
            controller: 'Spy'
            //resolve: {
            //    factory: scopeInit
            //}
        });
    $locationProvider.html5Mode(true);
}



