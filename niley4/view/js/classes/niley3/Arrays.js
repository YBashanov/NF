/**
 * Получить 1 элемент по паре {ключ : значение}
 * или по нескольким парам
 *
 * @param {key : value} или {key : value1, key2 : value2...}
 * @return object or null
 * */
Array.prototype.get = function(searchObj){
    var me = this;
    var meLength = me.length;
    var meObj;
    var isIt = true;
    
    if (searchObj){
        if (meLength > 0) {
            var _i = 0;
            for (var i=0; i<meLength; i++) {
                meObj = me[i];
                //сразу выставляем, что элемент объекта подходит по критерию,
                // и пробуем в цикле опровергнуть это утверждение
                isIt = true;

                for (var val in searchObj) {
                    if (meObj[val]) {
                        if (meObj[val] == searchObj[val]) {}
                        else {
                            isIt = false;
                            break;
                        }
                    }
                    //false будет почти всегда, за исключением того самого элемента
                    else {
                        isIt = false;
                        break;
                    }
                }

                if (isIt == true) {
                    _i = i;
                    break;
                }
            }
            if (isIt == true) {
                return me[_i];
            }
        }
    }
    return false;
};
/**
 * Удалить 1 элемент по одной паре {ключ : значение}
 * или по нескольким парам
 *
 * @param {key : value} или {key : value1, key2 : value2...}
 * */
Array.prototype.delete = function(searchObj){
    var me = this;
    var meLength = me.length;
    var meObj;
    var isIt = true;

    //надо чтобы одновременно совпадали все свойства
    if (searchObj){

        if (meLength > 0) {
            var _i = 0;
            for (var i=0; i<meLength; i++) {
                meObj = me[i];
                //сразу выставляем, что элемент объекта подходит по критерию,
                // и пробуем в цикле опровергнуть это утверждение
                isIt = true;

                for (var val in searchObj) {
                    if (meObj[val]) {
                        if (meObj[val] == searchObj[val]) {}
                        else {
                            isIt = false;
                            break;
                        }
                    }
                    //false будет почти всегда, за исключением того самого элемента
                    else {
                        isIt = false;
                        break;
                    }
                }

                if (isIt == true) {
                    _i = i;
                    break;
                }
            }

            if (isIt == true) {
                me.splice(_i, 1);
            }
        }
    }

    return isIt;
};


/**
 * Склеить 2 массива в один
 * Элементы второго приклеиваются к концу первого
 *
 * <b>Возвращает</b>
 * Array (объединенный)
 * */
Array.prototype.concat = function (array) {
    var length1 = this.length;
    var length2 = array.length;

    if (length2 > 0) {
        for (var i=0; i<length2; i++) {
            this.push(array[i]);
        }
    }
    return this;
};

/**
 * Найти максимальное число в массиве
 *-
 * <b>Возвращает</b>
 * Integer (или NaN)
 * */
Array.prototype.max = function () {
    return Math.max.apply(null, this);
};



