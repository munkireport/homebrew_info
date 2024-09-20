<div id="homebrew_info-tab"></div>
<h2 data-i18n="homebrew_info.clienttitle"></h2>
<div id="homebrew_info-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>


<script>
$(document).on('appReady', function(){
    $.getJSON(appUrl + '/module/homebrew_info/get_tab_data/' + serialNumber, function(d){
        if( ! d || d['homebrew_version'] == null){
            // Change loading message to no data
            $('#homebrew_info-msg').text(i18n.t('no_data'));

        } else {

            // Hide loading/no data message
            $('#homebrew_info-msg').text('');

            // Generate rows from data
            var rows = ''
            for (var prop in d){
                // Do nothing for empty values to blank them
                if ((d[prop] == '' || d[prop] == null) && d[prop] != "0"){
                    rows = rows

                // Format booleans
                } else if((prop == 'homebrew_noanalytics_this_run' || prop == "rosetta_2") && d[prop] == 1){
                    rows = rows + '<tr><th>'+i18n.t('homebrew_info.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                } else if((prop == 'homebrew_noanalytics_this_run' || prop == "rosetta_2") && d[prop] == 0){
                    rows = rows + '<tr><th>'+i18n.t('homebrew_info.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';

                // Else, build out rows 
                } else {
                    rows = rows + '<tr><th>'+i18n.t('homebrew_info.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                }
            }

            $('#homebrew_info-tab')
                .append($('<div style="max-width:800px;">')
                    .append($('<table>')
                        .addClass('table table-striped table-condensed')
                        .append($('<tbody>')
                            .append(rows))))
        }
    });
});
</script>
