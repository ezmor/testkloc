document.addEventListener('DOMContentLoaded', function () {
    const leadForm = document.getElementById('leadForm');
    
    if (leadForm) {
        leadForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const form = document.getElementById('leadForm');
            const formData = new FormData(form);

        

    $.ajax({
        url: 'https://crm.i1affleds.website/insert_lead.php', // Update the URL to your CRM's domain
        method: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        crossDomain: true,
        success: function (response) {
            if (response.success) {
                // Handle successful lead capture, e.g., show a success message or redirect to a thank you page
                // alert('Lead captured successfully!');

                // Make another AJAX request to execute your PHP script
                $.ajax({
                    url: './index.php', // Update the URL to your domain
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    crossDomain: true,
                    success: function (response) {
                        // Handle the success of the send.php execution, e.g., show a success message or perform other actions
                        console.log('send.php executed successfully:', response);
                        
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // Handle errors in the send.php execution, e.g., show an error message or perform other actions
                        console.log('Error executing send.php:', textStatus, errorThrown);
                    }
                }); // <--- Added missing closing parenthesis and semicolon

                const pixel_id = encodeURIComponent(formData.get('pixel_id'));
                const name = encodeURIComponent(formData.get('name'));
                const phone = encodeURIComponent(formData.get('phone'));
                const target = encodeURIComponent(/* Set target value here */);
                window.location.href = `/../thanks-cl/confirm.php?pixel_id=${pixel_id}&name=${name}&phone=${phone}&target=${target}`;

            } else {
                // Handle errors, e.g., show an error message or highlight the problematic fields
                // alert('There was an error capturing the lead. Please try again.');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 409) {
                // alert('A lead with the same click_id already exists.');
            } else {
                 alert('An error occurred while processing the request. Please try again.');
                 console.log('Error details:', textStatus, errorThrown);
            }
        }
    });


        });
    } else {
        console.error('Element with ID "leadForm" not found');
    }

});
