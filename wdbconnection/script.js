$(document).ready(function() {
  $('#rfidcard').focus();

  // Keep focus on RFID input
  $('body').mousemove(function() {
    $('#rfidcard').focus();
  });

  $('#rfidcard').on('keypress', function(e) {
    if (e.which === 13) {
      var rfidValue = $(this).val().trim();

      if (rfidValue.length >= 8) {
        $.ajax({
          url: "elder.php",
          method: "POST",
          data: { rfid: rfidValue },
          dataType: "json",
          success: function(response) {
            console.log(response);
            if (response.success) {
              $('#img').attr('src', response.image);
              $('#name').text("Name: " + response.name);
              $('#address').text("Address: " + response.address);
              $('#age').text("Age: " + response.age);
              $('#DOB').text("Date of Birth: " + response.DOB);
              $('#card_id').text("Card ID: " + response.card_id);
            } else {
              alert(response.message);
            }
          },
          error: function(xhr, status, error) {
            console.log("AJAX Error:", status, error, xhr.responseText);
            alert("Error connecting to database.");
          }
        });

        $(this).val("");
      } else {
        alert("Incomplete RFID scan.");
      }
    }
  });
});
