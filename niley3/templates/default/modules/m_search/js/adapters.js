if (! M_search) {
    a('M_search не определена, нельзя использовать адаптеры');
}
//адаптер по результатам загрузки фото - сразу ищем эту фотку
M_search.toUploadFile = function(data){
    M_search.searchClick(data);
}