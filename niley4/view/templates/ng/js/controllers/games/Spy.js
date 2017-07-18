'use strict';

App.controller('Spy', function ($scope, $http) {
    $scope.Spy = {

        /**
         * Создать новый стол
         * */
        createTable : function() {

            var data = {
                script : 'games/spy',
                name : 'createTable',
                data : {
                    'total' : '26a'
                }
            };
            $http.post('/php/ajax/niley4/post.php', data)
                .success(function (data) {
                    с(data);
                })
                .error(function (result) {
                    c(result);
                });
        },
        /**
         * Присоединиться к созданному столу
         * */
        joinTable : function(){
            c(2);
        },

        /**
         * Прочитать правила
         * */
        rules : function(){
            c(3);
        }
    };
});
