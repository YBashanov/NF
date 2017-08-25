/**
 * Объект типа {@link Convertor}
 * Перегоняет json-ы из неудобных форматов в формат для карты
 *
 * <b>Методы</b>
 * {@link Convertor.remainsToRegionStatistic} - из jsonа Остатков в json для подкраски регионов
 * {@link Convertor.kpiToRegionStatistic} - из jsonа Общий KPI в json для подкраски регионов
 * {@link Convertor.openToCityStatistic} - из jsonа Открытые инциденты в json Кружки городов
 * {@link Convertor.totalToCityStatistic} - из jsonа Инциденты-Общее количество в json Кружки городов
 * {@link Convertor.getJsonForSelect} - Сформировать json нужного формата для {@link _select_}
 * */
var _convertor_;
if (! Convertor) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link _convertor_}
     * */
    var Convertor = function(params){
        var me = this;

        if (params == undefined) params = {};


        /**
         * преобразовать: из jsonа Остатков в json для подкраски регионов
         * (из возвращаемого статистикой /activiti-rest/service/incident/statistics/remains?filters_id=)
         *
         * <b>Параметры</b>
         * json data - входящий json
         * String remains = remains_10 или remains_30
         * */
        me.remainsToRegionStatistic = function(data, remains){
            var newData = [];

            if (data) {
                var length = data.length;
                for (var i = 0; i < length; i++) {
                    newData.push({
                        id: data[i].id,
                        average: data[i].values[remains],
                        total : data[i].values[remains]
                    });
                }

                return newData;
            }
            else {
                c('remainsToRegionStatistic: нет данных');
                return null;
            }
        };


        /**
         * преобразовать: из jsonа Общий KPI в json для подкраски регионов
         * (из возвращаемого статистикой /activiti-rest/service/incident/statistics/kpi?filters_id=)
         *
         * <b>Параметры</b>
         * json data - входящий json
         * */
        me.kpiToRegionStatistic = function(data){
            var newData = [];

            if (data) {
                var length = data.length;
                for (var i = 0; i < length; i++) {

                    var total =
                        parseInt(data[i].values.average) +
                        parseInt(data[i].values.input) +
                        parseInt(data[i].values.internal) +
                        parseInt(data[i].values.output);

                    newData.push({
                        id: data[i].id,
                        average : data[i].values.average,
                        input   : data[i].values.input,
                        internal: data[i].values.internal,
                        output  : data[i].values.output,
                        total   : total
                    });
                }

                return newData;
            }
            else {
                c('kpiToRegionStatistic: нет данных');
                return null;
            }
        };


        /**
         * преобразовать: из jsonа Открытые инциденты в json Кружки городов
         *
         * <b>Параметры</b>
         * json data - входящий json
         * */
        me.openToCityStatistic = function(data){
            var newData = [];

            if (data) {
                var length = data.length;
                for (var i = 0; i < length; i++) {

                    var total =
                        parseInt(data[i].values.highcritical) +
                        parseInt(data[i].values.critical) +
                        parseInt(data[i].values.high) +
                        parseInt(data[i].values.medium) +
                        parseInt(data[i].values.low);

                    newData.push({
                        id: data[i].id,
                        radius1: data[i].values.highcritical,
                        radius2: data[i].values.critical,
                        radius3: data[i].values.high,
                        radius4: data[i].values.medium,
                        radius5: data[i].values.low,
                        total : total
                    });
                }

                return newData;
            }
            else {
                c('openToCityStatistic: нет данных');
                return null;
            }
        };


        /**
         * преобразовать: из jsonа Инциденты-Общее количество в json Кружки городов
         *
         * <b>Параметры</b>
         * json data - входящий json
         * */
        me.totalToCityStatistic = function(data){
            var newData = [];

            if (data) {
                var length = data.length;
                for (var i = 0; i < length; i++) {
                    var total =
                        parseInt(data[i].values.new) +
                        parseInt(data[i].values.onhold) +
                        parseInt(data[i].values.pending) +
                        parseInt(data[i].values.escalated) +
                        parseInt(data[i].values.closed);

                    newData.push({
                        id: data[i].id,
                        radius1: data[i].values.new,
                        radius2: data[i].values.onhold,
                        radius3: data[i].values.pending,
                        radius4: data[i].values.escalated,
                        radius5: data[i].values.closed,
                        total : total
                    });
                }

                return newData;
            }
            else {
                c('openToCityStatistic: нет данных');
                return null;
            }
        };


        /**
         * преобразовать: из jsonа Общий KPI в json для подкраски объекта (точки объекта)
         * (из возвращаемого статистикой /activiti-rest/service/incident/statistics/kpi?filters_id=...region=index)
         *
         * <b>Параметры</b>
         * json data - входящий json
         * */
        me.kpiToCityStatistic = function(data){
            var newData = [];

            if (data) {
                var length = data.length;
                for (var i = 0; i < length; i++) {

                    var total =
                        parseInt(data[i].values.average) +
                        parseInt(data[i].values.input) +
                        parseInt(data[i].values.internal) +
                        parseInt(data[i].values.output);

                    newData.push({
                        id: data[i].id,
                        bgcolor : data[i].values.average,
                        input   : data[i].values.input,
                        internal: data[i].values.internal,
                        output  : data[i].values.output,
                        total   : total
                    });
                }

                return newData;
            }
            else {
                c('kpiToRegionStatistic: нет данных');
                return null;
            }
        };


        /**
         * преобразовать: из jsonа Остатки в json для подкраски объекта (точки объекта)
         * (из возвращаемого статистикой /activiti-rest/service/incident/statistics/remains?filters_id=...region=index)
         *
         * <b>Параметры</b>
         * json data - входящий json
         * */
        me.remainsToCityStatistic = function (data, remains) {
            var newData = [];

            if (data) {
                var length = data.length;
                for (var i = 0; i < length; i++) {
                    newData.push({
                        id: data[i].id,
                        bgcolor: data[i].values[remains],
                        total : data[i].values[remains]
                    });
                }

                return newData;
            }
            else {
                c('remainsToRegionStatistic: нет данных');
                return null;
            }
        }


        /**
         * Сформировать json нужного формата (для объекта {@link _select_})
         *
         * <b>Параметры</b>
         * - data - geojson macroregions_regions
         * - [parent_id] - строка, выбранный макрорегион.
         * Если не указана, формирует только макрорегионы
         * Если указана, формирует регионы указанного макрорегиона
         *
         * <b>Возвращает</b>
         * json вида
         * [{
         *   "label" : "Москва",
         *   "value" : "Москва"
         * }]
         *
         * @return Array
         * */
        me.getJsonForSelect = function(data, parent_id){
            if (parent_id == undefined) parent_id = null;
            var newData = [];
            if (data.features) {
                var dataLength = data.features.length;
                for (var i = 0; i < dataLength; i++) {
                    var feature = data.features[i];
                    if (feature.properties.PARENT_ID == parent_id) {
                        newData.push({
                            "label": feature.properties.region,
                            "value": feature.properties.region
                        });
                    }
                }
                return newData;
            }
            else {
                c('getJsonForSelect: нет data.features');
                return null;
            }
        }
    }
}
