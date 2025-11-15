$(document).ready(function() {
  // Always focus RFID input
  $('#rfidcard').focus();
  $('body').click(() => $('#rfidcard').focus());

  // load attendance data from db on page load
  loadAttendance();

  function loadAttendance() {
    $.ajax({
      url: "forattendance/get_attendance.php",
      method: "GET",
      dataType: "json",
      success: function(response) {
        if (response.success) {
          let rows = "";

          if (response.data.length > 0) {
            response.data.forEach(record => {
              rows += `
                <tr data-card="${record.card_id}">
                  <td>${record.name}</td>
                  <td>${record.card_id}</td>
                  <td>${record.address}</td>
                  <td>${record.time_in}</td>
                  <td class="time_out">${record.time_out ? record.time_out : '-'}</td>
                </tr>`;
            });
          } else {
            rows = `<tr><td colspan="5" class="text-center">No attendance records yet</td></tr>`;
          }

          $('#attendanceTable').html(rows);
        }
      },
      error: function(xhr) {
        console.error("Load Attendance Error:", xhr.responseText);
      }
    });
  }

  // for rfid input
  $('#rfidcard').on('keypress', function(e) {
    if (e.which === 13) {
      var rfidValue = $(this).val().trim();

      if (rfidValue.length >= 4) {
        $.ajax({
          url: "elder.php",
          method: "POST",
          data: { rfid: rfidValue },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              // updates card info
              $('#img').attr('src', response.image);
              $('#name').text("Name: " + response.name);
              $('#age').text("Age: " + response.age);
              $('#DOB').text("Date of Birth: " + response.DOB);
              $('#card_id').text("Card ID: " + response.card_id);

              // record of attendance
              $.ajax({
                url: "forattendance/attendance.php",
                method: "POST",
                data: { rfid: rfidValue },
                dataType: "json",
                success: function(data) {
                  if (data.success) {
                    loadAttendance(); // this reload the updated attendance table
                  } else {
                    alert(data.message);
                  }
                },
                error: function(xhr) {
                  console.error("Attendance AJAX Error:", xhr.responseText);
                }
              });
            } else {
              alert(response.message);
            }
          },
          error: function(xhr) {
            console.error("Elder AJAX Error:", xhr.responseText);
          }
        });

        $(this).val(""); // clears rfid field
      } else {
        alert("Incomplete RFID scan.");
      }
    }
  });

  // for reste attendance button
  $('#resetBtn').click(function() {
    if (confirm("Are you sure you want to reset all attendance records?")) {
      $.ajax({
        url: "forattendance/reset_attendance.php",
        method: "POST",
        dataType: "json",
        success: function(response) {
          if (response.success) {
            alert("Attendance has been reset successfully!");
            $('#attendanceTable').html('<tr><td colspan="5" class="text-center">Attendance cleared</td></tr>');
          } else {
            alert("Failed to reset attendance: " + response.message);
          }
        },
        error: function(xhr) {
          console.error("Reset Error:", xhr.responseText);
        }
      });
    }
  });
});
