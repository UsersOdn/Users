
/******************************
 * show admin register popup
 */

function showAdvancedRegister(){


    document.getElementById("mainModalContent").innerHTML = '<section class="content">'+



        '</section><!-- /.content -->';
    $('#password-confirmation').on('change' , function () {
        check_repeat_password( "password-confirmation" , "checkrepassword", "passconfirmmsg", this.value , $('#password').val() );
    });
    $('#password').on('change' , function () {
        general_validation( "password" , "checkpassword", "passwordmsg", this.value , "password" );
    });
    $('#address').on('change' , function () {
        general_validation( "address" , "checkaddress", "addressmsg", this.value , "address" );
    });
    $('#email').on('change' , function () {
        general_validation("email", "checkemail", "emailmsg", this.value , "email" );
    });
    $('#lastname').on('keyup' , function () {
        general_validation( "lastname" , "checklastname", "lastnamemsg", this.value , "name" );
    });
    $('#firstname').on('keyup' , function () {

        general_validation("firstname", "checkfirstname", "namemsg", this.value , "name" );
    });
    $('#mobile').on('change' , function () {
        general_validation( "mobile" , "checkmobile", "mobilemsg", this.value , "mobile" );
    });
    $('#state').on('change' , function () {
        loadCities(this.value);
    });
    $('#city').on('change' , function () {
        loadRegions(this.value);
    });

}


function loadMainRolePage() {

    var obj = null;

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_all_roles'
        })
    ).done(function (data) {

            obj = jQuery.parseJSON(data);
            console.log("&&&&&&&&&&&&&"+data);
            //console.log("************"+keys);
            if (obj['act'] == 'data') {

                var keys = Object.keys(obj['data'][0]);
                var acts = obj['data'];
                /*
                 create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                 */
                var tableplace="mytable";
                var tableid= "maintable";
                var tablebodiid="gridview";
                var havereport=false;
                var haveoperation=true;
                var header = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];
                var footer = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];

                var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUser" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                var btno1= {type:"button", value:"اکشن ها", class:"btn btn-xs btn-warning", function:"loadRole",modal:"data-toggle='modal' data-target='#mainModal'"};

                var reportbtn=null;

                var operationbtn={0:btno0 , 1:btno1 };
                var params=null;


                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);


            }

            else if (obj['act'] == 'message') {
                prepare_message(obj['text'], 'fa' , obj['title'] );
                return null;
            }
        }
    );


}


function prepareArgsForSetRole(){
    var arg1=document.getElementById('roleName').value;
    var arg2=document.getElementById('roleDescription').value;
    setRole(arg1 , arg2);
}

function setRole(arg1 , arg2) {





    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'set_role',
            name:arg1,
            description:arg2
        })
    ).done(function (data) {
            console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            //var table=document.getElementById('maintable');

            if (obj['act'] == 'data') {





            }

            else if (obj['act'] == 'message') {



                var el = document.getElementById('maintable_wrapper');
                el.parentElement.removeChild(el);//innerHTML="secondtable_wrapper";

                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_all_roles'
                    })
                ).done(function (data) {

                        obj = jQuery.parseJSON(data);
                        console.log("&&&&&&&&&&&&&"+data);
                        //console.log("************"+keys);
                        if (obj['act'] == 'data') {

                            var keys = Object.keys(obj['data'][0]);
                            var acts = obj['data'];
                            /*
                             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                             */
                            var tableplace="mytable";
                            var tableid= "maintable";
                            var tablebodiid="gridview";
                            var havereport=false;
                            var haveoperation=true;
                            var header = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];
                            var footer = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];

                            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUser" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                            var btno1= {type:"button", value:"اکشن ها", class:"btn btn-xs btn-warning", function:"loadRole",modal:"data-toggle='modal' data-target='#mainModal'"};

                            var reportbtn=null;

                            var operationbtn={0:btno0 , 1:btno1 };
                            var params=null;


                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);


                        }

                        else if (obj['act'] == 'message') {
                            //prepare_message(obj['text'], 'fa' , obj['title'] );
                            return null;
                        }
                    }
                );

                console.log("%%%%%%%%%%%%%%%%"+obj['text']);
                // prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );
}
