$(document).ready(function(){

  var rfidcard = "3362817637";
  var name = "Ali";
  var address = "Tangway";
  var age = "23";
  var DOB = "Sept 26, 2002"; 
  var image = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
  var card_id = "3362817637";

  $('#rfidcard').focus();
  $('body').mousemove(function(){
    $('#rfidcard').focus();
  });

  // this detects when RFID input changes
  $('#rfidcard').on('input', function(){
    var value = $(this).val().trim();

    if (value.length >= 10) {
      if (value === rfidcard) {
        $('#img').attr('src', image);
        $('#name').text("Name: " + name);
        $('#address').text("Address: " + address);
        $('#age').text("Age: " + age);
        $('#DOB').text("Date of Birth: " + DOB);
        $('#card_id').text("Card ID: " + card_id);
      }
      $(this).val("");
    }
  });
});
