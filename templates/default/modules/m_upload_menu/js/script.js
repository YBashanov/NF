if (! M_upload_menu) var M_upload_menu = {
    start : function (){
        this.div = $(".m_upload_menu");
    },
    
    
    load : function(){
        var me = this;

        me.div.html(Wait.img());
        
        Functs.post(
            me.my_path + "ajax/load.php",
            {},
            function(data){

                if (data.success == "true") {
                    me.div.html(data.data);
                }
                else {
                    me.div.html("");
                    c(data.message);
                }
            }
        );
    }
}