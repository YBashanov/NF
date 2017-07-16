if (! M_voting) {
    Error.add("M_voting: модуль M_voting не определен");
}
else {
    M_voting.my_path = "http://"+server+"/templates/default/modules/m_voting/",
    
    //фон серых картинок
    M_voting.getBgGray = function(){
        return [
            "url("+M_voting.my_path+"image/vote_plus_20_gray.png)",
            "url("+M_voting.my_path+"image/vote_minus_20_gray.png)"
        ];
    }
    
    //фон цветных картинок
    M_voting.getBgColor = function(){
        return [
            "url("+M_voting.my_path+"image/vote_plus_20.png)",
            "url("+M_voting.my_path+"image/vote_minus_20.png)"
        ];
    }
}