 
    $(document).ready(function(){
       $('#textbox1').val('0');
       $('#sepurb').val('0');
        $('#sepurb').click(function() {
                if ($("#sepurb").is(":checked") == true) {
                    $('#textbox1').val('10000');
                    $('#sepurb').val(' 5R ');
                } else {
                    $('#textbox1').val('0');
                    $('#sepurb').val('0');
                }
            });
    }) 
     $(document).ready(function(){
       $('#textbox2').val('0');
       $('#limarb').val('0');
        $('#limarb').click(function() {
                if ($("#limarb").is(":checked") == true) {
                    $('#textbox2').val('50000');
                    $('#limarb').val(' 10R ');
                } else {
                    $('#textbox2').val('0');
                    $('#limarb').val('0');
                }
            });
    }) 
    $(document).ready(function(){
       $('#textbox3').val('0');
       $('#enmrb').val('0');
        $('#enmrb').click(function() {
                if ($("#enmrb").is(":checked") == true) {
                    $('#textbox3').val('60000');
                    $('#enmrb').val(' 15R ');
                } else {
                    $('#textbox3').val('0');
                    $('#enmrb').val('0');
                }
            });
    }) 
    $(document).ready(function(){
       $('#textbox4').val('0');
       $('#tjhrb').val('0');
        $('#tjhrb').click(function() {
                if ($("#tjhrb").is(":checked") == true) {
                    $('#textbox4').val('70000');
                    $('#tjhrb').val(' 25R ');
                } else {
                    $('#textbox4').val('0');
                    $('#tjhrb').val('0');
                }
            });
    }) 

    
    var currentDateTime = new Date();
    var year = currentDateTime.getFullYear();
    var month = (currentDateTime.getMonth() + 0);
    var date = (currentDateTime.getDate() + 0);
    
    if(date < 10) {
      date = '0' + date;
      }
    if(month < 10) {
      month = '0' + month;
      }

    var dateTomorrow = year + "-" + month + "-" + date;
    var bookingElem = document.querySelector("#tanggal");
    

    bookingElem.setAttribute("min", dateTomorrow);

    bookingElem.onchange = function () {
    }
