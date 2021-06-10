<!DOCTYPE html>
<html>
  <head>
    <title>Search Locations By Zip Code</title>
    <!-- (A) CSS -->
    <link href="style.css" rel="stylesheet" />
    <script
      src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"
      type="text/javascript"
    ></script>
  </head>

  <body>
    <div id="main-container">
      <h1>Search Locations By Zip Code</h1>
      <div class="container">
        <select name="country" id="country-select">
          <option value="">Please select a Country</option>
          <option value="AS">American Samoa</option>
          <option value="AD">Andorra</option>
          <option value="GG">Guernsey</option>
          <option value="GY">Guyana</option>
          <option value="JE">Jersey</option>
          <option value="MH">Marshall Islands</option>
          <option value="MP">Northern Mariana Islands</option>
          <option value="PM">Saint Pierre and Miquelon</option>
          <option value="SE">Sweden</option>
          <option value="US">United States</option>
        </select>
        <input
          type="text"
          id="zip-code"
          class="margin-top-20"
          name="zip-code"
          placeholder="ENTER ZIP CODE"
        />
        <button
          type="submit"
          id="submit-btn"
          value="Submit"
          class="margin-top-20"
        >
          Submit
        </button>
      </div>

      <div id="places-container">
        <table id="places">
          <thead>
            <tr class="tr-head">
              <th>country</th>
              <th>place name</th>
              <th>latitude</th>
              <th>longtitude</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div id="error-container"></div>

      <script type="text/JavaScript">
        $(document).on("change", "#country-select", function(){
            countryName = this.options[this.selectedIndex].text;
        });
        $(document).on("click", "#submit-btn", function() {
            const errorContainer = $("#error-container");
            errorContainer.empty();
            let country = $('#country-select').val();
            let zipCode = $('#zip-code').val();

            if(country === "" || zipCode === "") {
                alert("please fill both inputs");
                return;
            }
            let countryInfo = {
                "country":country,
                "zipCode":zipCode
            };
            // sending post request to db
            $.post('find_place.php', {'data':countryInfo}, function(response){
                    const data = JSON.parse(response);
                    const tbody = $('#places tbody');
                    if (data.length) {
                        $("#places").fadeIn();
                        tbody.empty();
                        // print the data to the user inside the table
                        for(i = 0; i < data.length; i++) {
                            const row = data[i];
                            const tr = $('<tr class = "tr-body"></tr>');
                            tr.append('<td class="padding-25">' + countryName + '</td>');
                            tr.append('<td class="padding-25">' + row.place_name + '</td>');
                            tr.append('<td class="padding-25">' + row.latitude + '</td>');
                            tr.append('<td class="padding-25">' + row.longtitude + '</td>');
                            tbody.append(tr);
                        }
                    }
                }).fail(function(data, textStatus, xhr) {
                    // alert relavant error to client
                    if(data.status == 404) {
                    errorContainer.append("<h1 class='error'>status 404: no matching zip code found in our system</h1>");
                    }
                    else if(data.status == 400) {
                     errorContainer.append("<h1 class='error'>status: 400 invalid values</h1>");
                    }
                    else if(data.status == 500) {
                        errorContainer.append("<h1 class='error'>status: 500 internal server error</h1>");
                    } else {
                     errorContainer.append("<h1 class='error'>ops..something went wrong</h1>");
                }
            });
        });
      </script>
    </div>
  </body>
</html>
