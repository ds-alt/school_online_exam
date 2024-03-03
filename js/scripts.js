document.addEventListener("DOMContentLoaded", function() {
    // Define the function to calculate the end time
    function calculateEndTime() {
        // Get the start time and duration values
        var startTime = document.getElementById("start_time").value;
        var duration = parseInt(document.getElementById("duration").value);

        // Check if the start time and duration are valid
        if (startTime && !isNaN(duration)) {
            // Parse the start time to a Date object
            var startDate = new Date(startTime);
            
            // Calculate the total minutes for the end time
            var totalEndMinutes = startDate.getHours() * 60 + startDate.getMinutes() + duration;
            
            // Calculate the hours and minutes for the end time
            var endHours = Math.floor(totalEndMinutes / 60) % 24; // Modulo 24 to ensure the value is within 0-23 range
            var endMinutes = totalEndMinutes % 60;

            // Get the current date in the required format
            var currentDate = new Date().toISOString().slice(0, 10); // Get only the date part

            // Format the end time as a string for input value
            var formattedEndTime = currentDate + "T" + (endHours < 10 ? "0" + endHours : endHours) + ":" + (endMinutes < 10 ? "0" + endMinutes : endMinutes);

            // Update the end time field
            document.getElementById("end_time").value = formattedEndTime;
        } else {
            // If either start time or duration is invalid, clear the end time field
            document.getElementById("end_time").value = "";
        }
    }

    // Listen for changes in the start time and duration fields
    document.getElementById("start_time").addEventListener("change", calculateEndTime);
    document.getElementById("duration").addEventListener("input", calculateEndTime);

    // Trigger the calculation once on page load
    calculateEndTime();
});
