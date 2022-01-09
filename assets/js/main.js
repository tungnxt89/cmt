;(function ($) {
    $(function () {
        var file = $('input[name=file_csv]');
        const file_address = $('input[name=file_address_csv]');
        let total_files = 0;
        let total_files_read_done = 0;
        let datas = [];
        let data_ma_to_khai = [];
        let total_data_insert = 0;
        let total_data_per_insert = 1000;
        let total_pages = 0;
        let page = 1;

        let elInfoTotalFiles = $('#total-files');
        const elInfoTotalRows = $('#total-rows');
        let elInfoProgress = $('#progress');

        let data_noi_tra = [];
        let data_lay_ho = [];

        const config = {
            delimiter: ",",  // auto-detect
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
            dsdn: 0,
            typeFile: ''
        }

        file.on('change', function (e) {
            let files = e.target.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const nameFile = file.name;
                const ext = nameFile.split('.')[1];

                if ('csv' === ext) {
                    total_files++;
                    const str_replace = '.' + ext;
                    config.dsdn = nameFile.replace(str_replace, '');
                    config.typeFile = 'ma_ds';
                    Papa.parse(file, config);
                }
            }

            elInfoTotalFiles.text(total_files);
        });

        file_address.on('change', function (e) {
            let files = e.target.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const nameFile = file.name;
                const ext = nameFile.split('.')[1];

                if ('csv' === ext) {
                    total_files++;
                    config.typeFile = 'ma_address';
                    Papa.parse(file, config);
                }
            }

            elInfoTotalFiles.text(total_files);
        });

        // Read file ds-noi-tra
        const urlFileDsNoiTra = $('input[name=file_ds_noi_tra]').val();
        //console.log(urlFileDsNoiTra);
        config.typeFile = 'ds_noi_tra';
        config.download = true;
        Papa.parse(urlFileDsNoiTra, config);

        // Read file ds-lay-ho
        const urlFileDsLayHo = $('input[name=file_ds_lay_ho]').val();
        //console.log(urlFileDsLayHo);
        config.typeFile = 'ds_lay_ho';
        config.download = true;
        Papa.parse(urlFileDsLayHo, config);

        function read_a_file_completed(results, file) {
            const config = $(this)[0];
            const data_file = results.data;

            switch (config.typeFile) {
                case 'ma_ds':
                    handle_file_ma_ds(data_file, config.dsdn);
                    break;
                case 'ma_address':
                    handle_file_ds_tong_hop(data_file);
                    break;
                case 'ds_noi_tra':
                    handle_file_ds_noi_tra(data_file);
                    break;
                case 'ds_noi_tra':
                    handle_file_ds_lay_ho(data_file);
                    break;
                default:
                    break;
            }
        }

        function handle_file_ma_ds(data_file, dsdn) {
            data_file.splice(0, 9);
            let data_file_right = [];

            $.each(data_file, function (i) {
                const row = data_file[i];
                row[0] = dsdn;
                const row_tmp = {};
                const value_check = row[1] + '';
                if (value_check !== undefined && value_check.length) {
                    data_ma_to_khai.push(row[1]);

                    row_tmp.ma_dsdn = row[0];
                    row_tmp.noi_tra = '';
                    row_tmp.lay_ho  = '';
                    row_tmp.ma_to_khai = row[1];
                    row_tmp.name = row[2];
                    row_tmp.so_cccd = row[4];
                    row_tmp.birthday = row[7];
                    row_tmp.sex = row[9];
                    row_tmp.address = row[10];

                    if(undefined !== data_noi_tra[row_tmp.ma_dsdn]) {
                        row_tmp.noi_tra = data_noi_tra[row_tmp.ma_dsdn];
                    }

                    if(undefined !== data_lay_ho[row_tmp.ma_to_khai]) {
                        row_tmp.lay_ho = data_lay_ho[row_tmp.ma_to_khai];
                    }

                    data_file_right.push(row_tmp);
                } else {
                    return false;
                }
            });

            datas = datas.concat(data_file_right);

            //console.log(datas);

            total_files_read_done++;

            if (total_files_read_done === total_files) {
                total_data_insert = datas.length;
                total_pages = total_data_insert / total_data_per_insert;
                elInfoTotalRows.text(total_data_insert);

                if (total_data_insert % total_data_per_insert !== 0) {
                    total_pages = Math.floor(total_data_insert / total_data_per_insert) + 1;
                }

                //Send data first
                const data_insert = datas.slice(0, total_data_per_insert);
                const data_ma_to_khai_insert = data_ma_to_khai.slice(0, total_data_per_insert);

                const params = {
                    page: 1,
                    total_pages: total_pages,
                    data_insert: data_insert,
                    data_ma_to_khai_insert: data_ma_to_khai_insert
                };

                elInfoProgress.text('1%');

                //const count = total_data_insert - 1;
                //console.log(datas[0]);
                //console.log(datas[count]);
                //console.log(datas[count-5]);

                handleAjax('/cmt/v1/create/users', params);
                
                //End
            }
        }

        function handle_file_ds_tong_hop(data_file) {
            data_file.splice(0, 12);

            //console.log(data_file);
            let data_file_right = [];

            $.each(data_file, function (i) {
                const row = data_file[i];
                const row_tmp = {};
                const value_check = row[1] + '';


                if (value_check !== undefined && value_check != 'undefined' && value_check.length) {
                    //console.log(value_check);

                    data_ma_to_khai.push(row[1]);

                    row_tmp.name = row[1];
                    row_tmp.sex = row[4];
                    row_tmp.birthday = row[5];
                    row_tmp.so_cccd = row[6];
                    row_tmp.address_full = row[11];
                    const addressArr = row[11].split(",");
                    const countAddressArr = addressArr.length;
                    addressArr.splice(0, (countAddressArr-3));
                    row_tmp.address_search = addressArr.join(",");

                    data_file_right.push(row_tmp);
                } else {
                    //continue;
                }
            });

            datas = datas.concat(data_file_right);

            //console.log(datas);

            total_files_read_done++;

            if (total_files_read_done === total_files) {
                total_data_insert = datas.length;
                total_pages = total_data_insert / total_data_per_insert;
                elInfoTotalRows.text(total_data_insert);

                if (total_data_insert % total_data_per_insert !== 0) {
                    total_pages = Math.floor(total_data_insert / total_data_per_insert) + 1;
                }

                //Send data first
                const data_update = datas.slice(0, total_data_per_insert);

                const params = {
                    page: 1,
                    total_pages: total_pages,
                    data_update: data_update,
                };

                handleAjax('/cmt/v1/update/users', params);
                //End
            }

        }

        function handle_file_ds_noi_tra(data_file) {
            $.each(data_file, function(i) {
                const row = data_file[i];

                if(row[0].length && row[1].length) {
                    data_noi_tra[row[0]] = row[1];
                }
            })

            //console.log(data_noi_tra);
        }

        function handle_file_ds_lay_ho(data_file) {
            $.each(data_file, function(i) {
                const row = data_file[i];

                if(row[0].length && row[1].length) {
                    data_lay_ho[row[0]] = row[1];
                }
            })

            //console.log(data_noi_tra);
        }

        function get_users(params) {
            let url = '/cmt/v1/users';

            wp.apiFetch({
                path: url,
                method: 'POST',
                data: params,
            }).then((res) => {
                const {status, message, data} = res;
                if (status === 'success') {
                    const el_wrapper_list_courses = $('#wrapper-list-users');
                    const el_tablenav = $('.tablenav');

                    el_wrapper_list_courses.html('');
                    el_tablenav.html('');


                    el_wrapper_list_courses.append(data.content);
                    el_tablenav.append(data.paginate);
                }
            }).catch((err) => {

            }).then(() => {

            });
        }

        // Foreach 100 row to insert table
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

                        setTimeout(function(){
                            handleAjax(url, params);
                        }, 300);
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
