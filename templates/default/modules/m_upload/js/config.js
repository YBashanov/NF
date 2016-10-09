if (! M_upload) {
    Error.add("M_upload: модуль M_upload не определен");
}
else {
    M_upload.thisFile = "M_upload";
    
    //путь до модуля
    M_upload.my_path = "http://"+server+"/templates/default/modules/m_upload/";
    
    //прелоадер
    M_upload.wait = M_upload.my_path + "image/wait.gif";
    
    //файл, обрабатывающий загрузку - это может быть один из уже настроенных mediaUpload_boundary
    M_upload.uploadFile = "http://"+server+"/ajax/listen/mediaUpload_boundary_public.php";
}