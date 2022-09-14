console.log("Its including js file");
$('document').ready(function(){

    // $('#btn').click(function(e)
    // {
    //     var fname= document.getElementById('fname').value;
    //     var lname= document.getElementById('lname').value;
        
    //     e.preventDefault();
    //     $.ajax({
    //         type:'post',
    //         url:'getData',
    //         data:{fname:fname,lname:lname},
    //         success:function(data)
    //         {
    //           alert(data);
    //         }
    //     });  
    // });

    $("#studentForm").submit(function(e) {
        e.preventDefault();    
        var formData = new FormData(this);
        var test=document.getElementById('file').value;

        $.ajax({
            url: "getData",
            type: 'POST',
            data: formData,
            success: function (data) {
                console.log(data)
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});