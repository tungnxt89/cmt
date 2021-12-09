;(function ($) {
    $(function () {
        let total_data_insert = 0;
        let total_data_per_insert = 5;
        let total_pages = 0;
        let page = 1;

        const config = {
            delimiter: "",  // auto-detect
            newline: "",    // auto-detect
            quoteChar: '"',
            escapeChar: '"',
            header: false,
            transformHeader: undefined,
            dynamicTyping: false,
            preview: 0,
            encoding: "",
            worker: false,
            comments: false,
            step: undefined,
            complete: read_a_file_completed,
            error: undefined,
            download: false,
            downloadRequestHeaders: undefined,
            downloadRequestBody: undefined,
            skipEmptyLines: false,
            chunk: undefined,
            chunkSize: undefined,
            fastMode: undefined,
            beforeFirstChunk: undefined,
            withCredentials: undefined,
            transform: undefined,
            delimitersToGuess: [',', '\t', '|', ';', Papa.RECORD_SEP, Papa.UNIT_SEP],
            typeFile: ''
        }

        var file = $('input[name=file_barcode_csv]');

        file.on('change', function (e) {
            let file = e.target.files[0];

            Papa.parse(file, config);
        });

        function read_a_file_completed(results, file) {
            const config = $(this)[0];
            const data_file = results.data;

            handle_file_barcode(data_file);
        }

        function handle_file_barcode(data_file) {
            let data_barcode = [];

            $.each(data_file, function (i) {
                const row = data_file[i];

                //const row_arr = row.split('|');
                
                let info = {};
                info.cccd = row[0];
                info.name = row[2];
                info.birthday = row[3];
                info.sex = row[4];
                info.address_full = row[5];
                info.ngay_cap = row[6];

                data_barcode.push(info);
            });
            
            total_data_insert = data_barcode.length;
            total_pages = total_data_insert / total_data_per_insert;
            //elInfoTotalRows.text(total_data_insert);

            if (total_data_insert % total_data_per_insert !== 0) {
                total_pages = Math.floor(total_data_insert / total_data_per_insert) + 1;
            }

            //Send data first
            const data_send = data_barcode.slice(0, total_data_per_insert);

            const params = {
                page: 1,
                total_pages: total_pages,
                data: data_send
            };

            console.log(params);

           //elInfoProgress.text('1%');

            handleAjax('/cmt/v1/barcode', params);
            //End
            
        }

        const handleAjax = function (url, params) {
            wp.apiFetch({
                path: url,
                method: 'POST',
                data: params,
            }).then((res) => {
                const {status, message, data} = res;
                if (status === 'success') {
                    //console.log(data.page, total_pages);

                    if (data.page <= total_pages) {
                        params.page = data.page;
                        const from = (params.page - 1) * total_data_per_insert;
                        const to = from + total_data_per_insert;

                        if (params.data_insert !== undefined) {
                            params.data_insert = datas.slice(from, to);
                            params.data_ma_to_khai_insert = data_ma_to_khai.slice(from, to);
                        } else if (params.data_update !== undefined) {
                            params.data_update = datas.slice(from, to);
                        }

                        const progressPercent = (params.page/total_pages * 100).toFixed(2);
                        elInfoProgress.text(progressPercent + '%');

                        handleAjax(url, params);
                    } else {
                        elInfoProgress.text('Hoàn thành');
                        alert('Tien trinh da hoan thanh');
                        window.location.reload()
                    }
                }
            }).catch((err) => {

            }).then(() => {

            });
        };
    });
}(jQuery));
