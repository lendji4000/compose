$(document).ready(function(){  
   /*$(this).delegate("#addDeviceForm","submit",function(e){
       e.preventDefault();
        loadEiAjaxForm($("#addDeviceForm"),$(".eiLoader"),"json",true,saveDevice);
   });*/
});
function saveDevice(response){
    if(response.success){
    }
    else{
        $("#addDeviceForm").replaceWith(response.html);
        $("#addDevice").modal('show');
    }
}