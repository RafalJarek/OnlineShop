$(function(){
        $(document).ready(function() {
          //alert('ready');
      
          $('input[id=change]').on('keyup', function(event) {
            var arr = $(this).val().split(' '); 
            var result = ""; 
            for (var i=0; i<arr.length; i++){ 
                result+=arr[i].substring(0,1).toUpperCase() + arr[i].substring(1).toLowerCase();
                if (i < arr.length-1) {
                    result += ' ';
                }
            } 
            $(this).val(result); 
        })
    });
});