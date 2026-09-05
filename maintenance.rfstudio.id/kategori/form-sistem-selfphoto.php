<?php
usleep(1000000);
require "../function.php";
    error_reporting(0);
    $tanggal = $_POST["tanggal"];
    $tanggalkartini = $_POST["tanggalkartini"];
    $ambiljadwalliburkab = mysqli_query($koneksi,"SELECT * FROM jadwal_libur_kab WHERE jadwal LIKE '%$tanggal%'" );
    $ambiljadwalliburkota = mysqli_query($koneksi,"SELECT * FROM jadwal_libur_kota WHERE jadwal LIKE '%$tanggalkartini%'" );
    $ambildatastudioKab = mysqli_query($koneksi,"SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal LIKE '%$tanggal%' OR studio = 'Bekasi-Kabupaten' AND studio = '' 
                                                  UNION 
                                                  SELECT * FROM data_booking_cobahampirfinishjuga WHERE tanggal LIKE '%$tanggal%' OR studio = 'Bekasi-Kabupaten' AND studio = '';");
    $dataKab = mysqli_fetch_array($ambildatastudioKab);
    $ambildatastudioKot = mysqli_query($koneksi,"SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE studio = 'Bekasi-Kota' AND tanggal LIKE '%$tanggalkartini%' 
                                                  UNION 
                                                  SELECT * FROM data_booking_cobahampirfinishjuga WHERE studio = 'Bekasi-Kota' AND tanggal LIKE '%$tanggalkartini%'");
    $dataKot = mysqli_fetch_array($ambildatastudioKot);
    if(isset($_POST["tanggal"])){
      if(mysqli_num_rows($ambiljadwalliburkab)>0){
        ?>
        <div class="text-center mt-3 mb-3">
        <h5>  Mohon maaf kami tutup </br>di tanggal <?php $tanggaledit = date("d-m-Y", strtotime($tanggal));
                        echo $tanggaledit?> </h5>
        </div>
        <?php
      }else{
        if(mysqli_num_rows($ambildatastudioKab)>0){
          ?>
            <div class="elem-group mx-auto inlined ms-2 me-2 mt-2 mb-2 bekasi-kabupaten">
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab1 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('08:00-08:30','08.00-08.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('08:00-08:30','08.00-08.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab1 = mysqli_fetch_array($ambiljamkab1);
                    if($jamkab1){
                    ?>
                    <button id="btn1" type="button" class="btn btn-outline-dark "  style="font-size:11px;" disabled>08:00-08:30</button>
                    <input id="jam1"  type="radio" hidden name="jampost" class="radio" value="08:00-08:30" disabled/>
                    <?php }else{?>
                      <button id="btn1" type="button" class="btn btn-outline-dark "  style="font-size:11px;">08:00-08:30</button>
                      <input id="jam1"  type="radio" hidden name="jampost" class="radio" value="08:00-08:30" />
                    <?php } ?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab2 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('09:00-09:30','09.00-09.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('09:00-09:30','09.00-09.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab2 = mysqli_fetch_array($ambiljamkab2);
                    if($jamkab2){
                      ?>
                      <button id="btn2" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>09:00-09:30</button>
                      <input id="jam2"  type="radio" hidden name="jampost" class="radio" value="09:00-09:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btn2" type="button" class="btn btn-outline-dark"  style="font-size:11px;">09:00-09:30</button>
                    <input id="jam2"  type="radio" hidden name="jampost" class="radio" value="09:00-09:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab3 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('10:00-10:30','10.00-10.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('10:00-10:30','10.00-10.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab3 = mysqli_fetch_array($ambiljamkab3);
                    if($jamkab3){
                      ?>
                    <button id="btn3" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>10:00-10:30</button>
                    <input id="jam3"  type="radio" hidden name="jampost" class="radio" value="10:00-10:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btn3" type="button" class="btn btn-outline-dark"  style="font-size:11px;">10:00-10:30</button>
                    <input id="jam3"  type="radio" hidden name="jampost" class="radio" value="10:00-10:30"/>
                    <?php } ?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab4 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('11:00-11:30','11.00-11.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('11:00-11:30','11.00-11.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab4 = mysqli_fetch_array($ambiljamkab4);
                    if($jamkab4){
                      ?>
                      <button id="btn4" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>11:00-11:30</button>
                      <input id="jam4"  type="radio" hidden name="jampost" class="radio" value="11:00-11:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btn4" type="button" class="btn btn-outline-dark"  style="font-size:11px;">11:00-11:30</button>
                    <input id="jam4"  type="radio" hidden name="jampost" class="radio" value="11:00-11:30"/>
                    <?php }?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab5 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('12:00-12:30','12.00-12.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('12:00-12:30','12.00-12.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab5 = mysqli_fetch_array($ambiljamkab5);
                    if($jamkab5){
                      ?>
                    <button id="btn5" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>12:00-12:30</button>
                    <input id="jam5"  type="radio" hidden name="jampost" class="radio" value="12:00-12:30" disabled/>
                    <?php 
                    }else{?>
                    <button id="btn5" type="button" class="btn btn-outline-dark"  style="font-size:11px;">12:00-12:30</button>
                    <input id="jam5"  type="radio" hidden name="jampost" class="radio" value="12:00-12:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab6 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('13:00-13:30','13.00-13.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('13:00-13:30','13.00-13.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab6 = mysqli_fetch_array($ambiljamkab6);
                    if($jamkab6){?>
                      <button id="btn6" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>13:00-13:30</button>
                      <input id="jam6"  type="radio" hidden name="jampost" class="radio" value="13:00-13:30" disabled/>
                      <?php }else{?>
                    <button id="btn6" type="button" class="btn btn-outline-dark"  style="font-size:11px;">13:00-13:30</button>
                    <input id="jam6"  type="radio" hidden name="jampost" class="radio" value="13:00-13:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2" >
                    <?php
                    $ambiljamkab7 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('14:00-14:30','14.00-14.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('14:00-14:30','14.00-14.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab7 = mysqli_fetch_array($ambiljamkab7);
                    if($jamkab7){?>
                      <button id="btn7" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>14:00-14:30</button>
                      <input id="jam7"  type="radio" hidden name="jampost" class="radio" value="14:00-14:30" disabled/>
                      <?php }else{?>
                      <button id="btn7" type="button" class="btn btn-outline-dark"  style="font-size:11px;">14:00-14:30</button>
                      <input id="jam7"  type="radio" hidden name="jampost" class="radio" value="14:00-14:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab8 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('15:00-15:30','15.00-15.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('15:00-15:30','15.00-15.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab8 = mysqli_fetch_array($ambiljamkab8);
                    if($jamkab8){?>
                      <button id="btn8" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>15:00-15:30</button>
                      <input id="jam8"  type="radio" hidden name="jampost" class="radio" value="15:00-15:30" disabled/>
                    <?php }else{?>
                      <button id="btn8" type="button" class="btn btn-outline-dark"  style="font-size:11px;">15:00-15:30</button>
                      <input id="jam8"  type="radio" hidden name="jampost" class="radio" value="15:00-15:30"/>
                    <?php }?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2"> 
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab9 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('16:00-16:30','16.00-16.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('16:00-16:30','16.00-16.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab9 = mysqli_fetch_array($ambiljamkab9);
                    if($jamkab9){?>
                      <button id="btn9" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>16:00-16:30</button>
                      <input id="jam9"  type="radio" hidden name="jampost" class="radio" value="16:00-16:30" disabled/>  
                    <?php }else{?>
                      <button id="btn9" type="button" class="btn btn-outline-dark"  style="font-size:11px;">16:00-16:30</button>
                      <input id="jam9"  type="radio" hidden name="jampost" class="radio" value="16:00-16:30"/>
                      <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab10 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('17:00-17:30','17.00-17.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('17:00-17:30','17.00-17.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab10 = mysqli_fetch_array($ambiljamkab10);
                    if($jamkab10){?>
                      <button id="btn10" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>17:00-17:30</button>
                      <input id="jam10"  type="radio" hidden name="jampost" class="radio" value="17:00-17:30" disabled/>  
                    <?php }else{?>
                      <button id="btn10" type="button" class="btn btn-outline-dark"  style="font-size:11px;">17:00-17:30</button>
                      <input id="jam10"  type="radio" hidden name="jampost" class="radio" value="17:00-17:30"/>
                      <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab11 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('18:00-18:30','18.00-18.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('18:00-18:30','18.00-18.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab11 = mysqli_fetch_array($ambiljamkab11);
                    if($jamkab11){?>
                      <button id="btn11" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>18:00-18:30</button>
                      <input id="jam11"  type="radio" hidden name="jampost" class="radio" value="18:00-18:30" disabled/>  
                    <?php }else{?>
                      <button id="btn11" type="button" class="btn btn-outline-dark"  style="font-size:11px;">18:00-18:30</button>
                      <input id="jam11"  type="radio" hidden name="jampost" class="radio" value="18:00-18:30"/>
                      <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab12 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('19:00-19:30','19.00-19.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('19:00-19:30','19.00-19.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab12 = mysqli_fetch_array($ambiljamkab12);
                    if($jamkab12){
                    ?>
                      <button id="btn12" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>19:00-19:30</button>
                      <input id="jam12"  type="radio" hidden name="jampost" class="radio" value="19:00-19:30" disabled/>  
                    <?php }else{?>
                      <button id="btn12" type="button" class="btn btn-outline-dark"  style="font-size:11px;">19:00-19:30</button>
                      <input id="jam12"  type="radio" hidden name="jampost" class="radio" value="19:00-19:30"/>
                      <?php }?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab13 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('20:00-20:30','20.00-20.30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('20:00-20:30','20.00-20.30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab13 = mysqli_fetch_array($ambiljamkab13);
                    if($jamkab13){
                    ?>
                      <button id="btn13" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>20:00-20:30</button>
                      <input id="jam13"  type="radio" hidden name="jampost" class="radio" value="20:00-20:30" disabled/>  
                    <?php }else{?>
                      <button id="btn13" type="button" class="btn btn-outline-dark"  style="font-size:11px;">20:00-20:30</button>
                      <input id="jam13"  type="radio" hidden name="jampost" class="radio" value="20:00-20:30"/>
                      <?php }?>
                  </span>
                </div>
                <input type="text" name="uniqid" hidden class="radio" value="<?php $random = random_bytes(3); $uniqid = (bin2hex($random));echo $uniqid;?>"/>
                <div class="text-center">
                  <button id="clear" type="button" class="btn btn-outline-danger"  style="font-size:11px;"><i class="fa-solid fa-trash-can"></i> clear</button>
                </div>
                </div> 
                <div class="text-center">
                    <button id="btnsubmit" type="submit" name="submit" class="btn btn-dark my-auto mt-3" style="width:fit-content;border-radius:0px;font-size:14px"> Oke lanjut > </button>
                  </div>                       
            </div>
          <?php          
        }else{
          ?>
          <div class="elem-group mx-auto inlined ms-2 me-2 mt-2 mb-2">
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                      <button id="btn1" type="button" class="btn btn-outline-dark "  style="font-size:11px;">08:00-08:30</button>
                      <input id="jam1"  type="radio" hidden name="jampost" class="radio" value="08:00-08:30" />
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btn2" type="button" class="btn btn-outline-dark"  style="font-size:11px;">09:00-09:30</button>
                    <input id="jam2"  type="radio" hidden name="jampost" class="radio" value="09:30-10:30"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btn3" type="button" class="btn btn-outline-dark"  style="font-size:11px;">10.00-10.30</button>
                    <input id="jam3"  type="radio" hidden name="jampost" class="radio" value="10.00-10.30"/> 
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btn4" type="button" class="btn btn-outline-dark"  style="font-size:11px;">11.00-11.30</button>
                    <input id="jam4"  type="radio" hidden name="jampost" class="radio" value="11.00-11.30"/>
                  </span>
                </div>
                  
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <button id="btn5" type="button" class="btn btn-outline-dark"  style="font-size:11px;">12.00-12.30</button>
                    <input id="jam5"  type="radio" hidden name="jampost" class="radio" value="12.00-12.30"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btn6" type="button" class="btn btn-outline-dark"  style="font-size:11px;">13.00-13.30</button>
                    <input id="jam6"  type="radio" hidden name="jampost" class="radio" value="13.00-13.30"/>
                  </span>
                  <span class="button-radio mx-2" >
                      <button id="btn7" type="button" class="btn btn-outline-dark"  style="font-size:11px;">14.00-14.30</button>
                      <input id="jam7"  type="radio" hidden name="jampost" class="radio" value="14.00-14.30"/>     
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btn8" type="button" class="btn btn-outline-dark"  style="font-size:11px;">15.00-15.30</button>
                      <input id="jam8"  type="radio" hidden name="jampost" class="radio" value="15.00-15.30"/>                   
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                      <button id="btn9" type="button" class="btn btn-outline-dark"  style="font-size:11px;">16.00-16.30</button>
                      <input id="jam9"  type="radio" hidden name="jampost" class="radio" value="16.00-16.30"/>
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btn10" type="button" class="btn btn-outline-dark"  style="font-size:11px;">17.00-17.30</button>
                      <input id="jam10"  type="radio" hidden name="jampost" class="radio" value="17.00-17.30"/>
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btn11" type="button" class="btn btn-outline-dark"  style="font-size:11px;">18.00-18.30</button>
                      <input id="jam11"  type="radio" hidden name="jampost" class="radio" value="18.00-18.30"/>
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btn12" type="button" class="btn btn-outline-dark"  style="font-size:11px;">19.00-19.30</button>
                      <input id="jam12"  type="radio" hidden name="jampost" class="radio" value="19.00-19.30"/>
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btn13" type="button" class="btn btn-outline-dark"  style="font-size:11px;">20.00-20.30</button>
                      <input id="jam13"  type="radio" hidden name="jampost" class="radio" value="20.00-20.30"/>
                  </span>
                </div>
                <input type="text" name="uniqid" hidden class="radio" value="<?php $random = random_bytes(3); $uniqid = (bin2hex($random));echo $uniqid;?>"/>
                <div class="text-center">
                  <button id="clear" type="button" class="btn btn-outline-danger" style="font-size:11px;"><i class="fa-solid fa-trash-can"></i> clear</button>
                </div>
                </div> 
                <div class="text-center">
                    <button id="btnsubmit" type="submit" name="submit" class="btn btn-dark my-auto mt-3" style="width:fit-content;border-radius:0px;font-size:14px"> Oke lanjut > </button>
                  </div>                       
            </div>
          <?php
        }
      }
    }elseif(isset($_POST["tanggalkartini"])){
      if(mysqli_num_rows($ambiljadwalliburkota)>0){
        ?>
        <div class="text-center mt-3 mb-3">
        <h5>  Mohon maaf kami tutup </br>di tanggal <?php $tanggaleditkota = date("d-m-Y", strtotime($tanggalkartini));
                        echo $tanggaleditkota?> </h5>
        </div>
        <?php
      }else{
        if(mysqli_num_rows($ambildatastudioKot)>0){
          
          ?>
            <div class="elem-group mx-auto inlined ms-2 me-2 mt-2 mb-2 bekasi-kota">
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab1 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('08:00-08:30','08.00-08.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('08:00-08:30','08.00-08.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab1 = mysqli_fetch_array($ambiljamkab1);
                    if($jamkab1){
                    ?>
                    <button id="btnkartini1" type="button" class="btn btn-outline-dark "  style="font-size:11px;" disabled>08:00-08:30</button>
                    <input id="jamkartini1"  type="radio" hidden name="jampost" class="radio" value="08:00-08:30" disabled/>
                    <?php }else{?>
                      <button id="btnkartini1" type="button" class="btn btn-outline-dark "  style="font-size:11px;">08:00-08:30</button>
                      <input id="jamkartini1"  type="radio" hidden name="jampost" class="radio" value="08:00-08:30" />
                    <?php } ?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab2 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('09:00-09:30','09.00-09.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('09:00-09:30','09.00-09.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab2 = mysqli_fetch_array($ambiljamkab2);
                    if($jamkab2){
                      ?>
                      <button id="btnkartini2" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>09:00-09:30</button>
                      <input id="jamkartini2"  type="radio" hidden name="jampost" class="radio" value="09:00-09:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btnkartini2" type="button" class="btn btn-outline-dark"  style="font-size:11px;">09:00-09:30</button>
                    <input id="jamkartini2"  type="radio" hidden name="jampost" class="radio" value="09:00-09:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab3 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('10:00-10:30','10.00-10.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('10:00-10:30','10.00-10.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab3 = mysqli_fetch_array($ambiljamkab3);
                    if($jamkab3){
                      ?>
                    <button id="btnkartini3" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>10:00-10:30</button>
                    <input id="jamkartini3"  type="radio" hidden name="jampost" class="radio" value="10:00-10:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btnkartini3" type="button" class="btn btn-outline-dark"  style="font-size:11px;">10:00-10:30</button>
                    <input id="jamkartini3"  type="radio" hidden name="jampost" class="radio" value="10:00-10:30"/>
                    <?php } ?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab4 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('11:00-11:30','11.00-11.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('11:00-11:30','11.00-11.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab4 = mysqli_fetch_array($ambiljamkab4);
                    if($jamkab4){
                      ?>
                      <button id="btnkartini4" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>11:00-11:30</button>
                      <input id="jamkartini4"  type="radio" hidden name="jampost" class="radio" value="11:00-11:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btnkartini4" type="button" class="btn btn-outline-dark"  style="font-size:11px;">11:00-11:30</button>
                    <input id="jamkartini4"  type="radio" hidden name="jampost" class="radio" value="11:00-11:30"/>
                    <?php }?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab5 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('12:00-12:30','12.00-12.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('12:00-12:30','12.00-12.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab5 = mysqli_fetch_array($ambiljamkab5);
                    if($jamkab5){
                      ?>
                    <button id="btnkartini5" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>12:00-12:30</button>
                    <input id="jamkartini5"  type="radio" hidden name="jampost" class="radio" value="12:00-12:30" disabled/>
                    <?php 
                    }else{?>
                    <button id="btnkartini5" type="button" class="btn btn-outline-dark"  style="font-size:11px;">12:00-12:30</button>
                    <input id="jamkartini5"  type="radio" hidden name="jampost" class="radio" value="12:00-12:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab6 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('13:00-13:30','13.00-13.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('13:00-13:30','13.00-13.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab6 = mysqli_fetch_array($ambiljamkab6);
                    if($jamkab6){?>
                      <button id="btnkartini6" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>13:00-13:30</button>
                      <input id="jamkartini6"  type="radio" hidden name="jampost" class="radio" value="13:00-13:30" disabled/>
                      <?php }else{?>
                    <button id="btnkartini6" type="button" class="btn btn-outline-dark"  style="font-size:11px;">13:00-13:30</button>
                    <input id="jamkartini6"  type="radio" hidden name="jampost" class="radio" value="13:00-13:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2" >
                    <?php
                    $ambiljamkab7 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('14:00-14:30','14.00-14.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('14:00-14:30','14.00-14.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab7 = mysqli_fetch_array($ambiljamkab7);
                    if($jamkab7){?>
                      <button id="btnkartini7" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>14:00-14:30</button>
                      <input id="jamkartini7"  type="radio" hidden name="jampost" class="radio" value="14:00-14:30" disabled/>
                      <?php }else{?>
                      <button id="btnkartini7" type="button" class="btn btn-outline-dark"  style="font-size:11px;">14:00-14:30</button>
                      <input id="jamkartini7"  type="radio" hidden name="jampost" class="radio" value="14:00-14:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab8 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('15:00-15:30','15.00-15.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('15:00-15:30','15.00-15.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab8 = mysqli_fetch_array($ambiljamkab8);
                    if($jamkab8){?>
                      <button id="btnkartini8" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>15:00-15:30</button>
                      <input id="jamkartini8"  type="radio" hidden name="jampost" class="radio" value="15:00-15:30" disabled/>
                    <?php }else{?>
                      <button id="btnkartini8" type="button" class="btn btn-outline-dark"  style="font-size:11px;">15:00-15:30</button>
                      <input id="jamkartini8"  type="radio" hidden name="jampost" class="radio" value="15:00-15:30"/>
                    <?php }?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2"> 
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab9 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('16:00-16:30','16.00-16.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('16:00-16:30','16.00-16.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab9 = mysqli_fetch_array($ambiljamkab9);
                    if($jamkab9){?>
                      <button id="btnkartini9" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>16:00-16:30</button>
                      <input id="jamkartini9"  type="radio" hidden name="jampost" class="radio" value="16:00-16:30" disabled/>  
                    <?php }else{?>
                      <button id="btnkartini9" type="button" class="btn btn-outline-dark"  style="font-size:11px;">16:00-16:30</button>
                      <input id="jamkartini9"  type="radio" hidden name="jampost" class="radio" value="16:00-16:30"/>
                      <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkab10 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('17:00-17:30','17.00-17.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('17:00-17:30','17.00-17.30') AND studio IN ('Bekasi-Kota');");
                    $jamkab10 = mysqli_fetch_array($ambiljamkab10);
                    if($jamkab10){?>
                      <button id="btnkartini10" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>17:00-17:30</button>
                      <input id="jamkartini10"  type="radio" hidden name="jampost" class="radio" value="17:00-17:30" disabled/>  
                    <?php }else{?>
                      <button id="btnkartini10" type="button" class="btn btn-outline-dark"  style="font-size:11px;">17:00-17:30</button>
                      <input id="jamkartini10"  type="radio" hidden name="jampost" class="radio" value="17:00-17:30"/>
                      <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkota11 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('18:00-18:30','18.00-18.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('18:00-18:30','18.00-18.30') AND studio IN ('Bekasi-Kota');");
                    $jamkota11 = mysqli_fetch_array($ambiljamkota11);
                    if($jamkota11){?>
                      <button id="btnkartini11" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>18:00-18:30</button>
                      <input id="jamkartini11"  type="radio" hidden name="jampost" class="radio" value="18:00-18:30" disabled/>  
                    <?php }else{?>
                      <button id="btnkartini11" type="button" class="btn btn-outline-dark"  style="font-size:11px;">18:00-18:30</button>
                      <input id="jamkartini11"  type="radio" hidden name="jampost" class="radio" value="18:00-18:30"/>
                      <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkota12 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('19:00-19:30','19.00-19.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('19:00-19:30','19.00-19.30') AND studio IN ('Bekasi-Kota');");
                    $jamkota12 = mysqli_fetch_array($ambiljamkota12);
                    if($jamkota12){
                    ?>
                      <button id="btnkartini12" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>19:00-19:30</button>
                      <input id="jamkartini12"  type="radio" hidden name="jampost" class="radio" value="19:00-19:30" disabled/>  
                    <?php }else{?>
                      <button id="btnkartini12" type="button" class="btn btn-outline-dark"  style="font-size:11px;">19:00-19:30</button>
                      <input id="jamkartini12"  type="radio" hidden name="jampost" class="radio" value="19:00-19:30"/>
                      <?php }?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <?php 
                    $ambiljamkota13 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('20:00-20:30','20.00-20.30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('20:00-20:30','20.00-20.30') AND studio IN ('Bekasi-Kota');");
                    $jamkota13 = mysqli_fetch_array($ambiljamkota13);
                    if($jamkota13){
                    ?>
                      <button id="btnkartini13" type="button" class="btn btn-outline-dark"  style="font-size:11px;" disabled>20:00-20:30</button>
                      <input id="jamkartini13"  type="radio" hidden name="jampost" class="radio" value="20:00-20:30" disabled/>  
                    <?php }else{?>
                      <button id="btnkartini13" type="button" class="btn btn-outline-dark"  style="font-size:11px;">20:00-20:30</button>
                      <input id="jamkartini13"  type="radio" hidden name="jampost" class="radio" value="20:00-20:30"/>
                      <?php }?>
                  </span>
                </div>
                <input type="text" name="uniqid" hidden class="radio" value="<?php $random = random_bytes(3); $uniqid = (bin2hex($random));echo $uniqid;?>"/>
                <div class="text-center">
                  <button id="clearkartini" type="button" class="btn btn-outline-danger"  style="font-size:11px;"><i class="fa-solid fa-trash-can"></i> clear</button>
                </div>
                </div> 
                <div class="text-center">
                    <button id="btnsubmitkartini" type="submit" name="submit" class="btn btn-dark my-auto mt-3" style="width:fit-content;border-radius:0px;font-size:14px"> Oke lanjut > </button>
                  </div>                       
            </div>
          <?php          
        }else{
          ?>
          <div class="elem-group mx-auto inlined ms-2 me-2 mt-2 mb-2">
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                      <button id="btnkartini1" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">08:00-08:30</button>
                      <input id="jamkartini1"  type="radio" hidden name="jampost" class="radio" value="08:00-08:30" />
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btnkartini2" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">09:30-10:30</button>
                    <input id="jamkartini2"  type="radio" hidden name="jampost" class="radio" value="09:30-10:30"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btnkartini3" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">10.00-10.30</button>
                    <input id="jamkartini3"  type="radio" hidden name="jampost" class="radio" value="10.00-10.30"/> 
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btnkartini4" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">11.00-11.30</button>
                    <input id="jamkartini4"  type="radio" hidden name="jampost" class="radio" value="11.00-11.30"/>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <button id="btnkartini5" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">12.00-12.30</button>
                    <input id="jamkartini5"  type="radio" hidden name="jampost" class="radio" value="12.00-12.30"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btnkartini6" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">13.00-13.30</button>
                    <input id="jamkartini6"  type="radio" hidden name="jampost" class="radio" value="13.00-13.30"/>
                  </span>
                  <span class="button-radio mx-2" >
                      <button id="btnkartini7" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">14.00-14.30</button>
                      <input id="jamkartini7"  type="radio" hidden name="jampost" class="radio" value="14.00-14.30"/>     
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btnkartini8" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">15.00-15.30</button>
                      <input id="jamkartini8"  type="radio" hidden name="jampost" class="radio" value="15.00-15.30"/>                   
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                      <button id="btnkartini9" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">16.00-16.30</button>
                      <input id="jamkartini9"  type="radio" hidden name="jampost" class="radio" value="16.00-16.30"/>
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btnkartini10" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">17.00-17.30</button>
                      <input id="jamkartini10"  type="radio" hidden name="jampost" class="radio" value="17.00-17.30"/>
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btnkartini11" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">18.00-18.30</button>
                      <input id="jamkartini11"  type="radio" hidden name="jampost" class="radio" value="18.00-18.30"/>
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btnkartini12" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">19.00-19.30</button>
                      <input id="jamkartini12"  type="radio" hidden name="jampost" class="radio" value="19.00-19.30"/>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                      <button id="btnkartini13" type="button" class="btn btn-outline-dark kartini"  style="font-size:11px;">20.00-20.30</button>
                      <input id="jamkartini13"  type="radio" hidden name="jampost" class="radio" value="20.00-20.30"/>
                  </span>
                </div>
                <input type="text" name="uniqid" hidden class="radio" value="<?php $random = random_bytes(3); $uniqid = (bin2hex($random));echo $uniqid;?>"/>
                <div class="text-center">
                  <button id="clearkartini" type="button" class="btn btn-outline-danger"  style="font-size:11px;"><i class="fa-solid fa-trash-can"></i> clear</button>
                </div>
                </div> 
                <div class="text-center">
                    <button id="btnkartinisubmit" type="submit" name="submit" class="btn btn-dark my-auto mt-3" style="width:fit-content;border-radius:0px;font-size:14px"> Oke lanjut > </button>
                  </div>                       
            </div>
          <?php
        }
      }
    }
?>
<!DOCTYPE html>
<html>
  <head>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> -->
</head>
<body>
  <script>
    // $('.bekasi-kabupaten').show();
    // $('.bekasi-kota').hide();
    $("#btnsubmit").prop('disabled',true);
    $("#btnkartinisubmit").prop('disabled',true);
    $('.btn-outline-dark').click(function() {
      $("#btnsubmit").prop('disabled',false);
      $("#btnkartinisubmit").prop('disabled',false);
      // if( $('input.radio').is(':checked') ){
          
      // }
      // else{
      //     $("#btnsubmit").prop('disabled',true);
      // }
    }); 
    

      $('#btn1').click(function() {
          $('#btn1').addClass('active');
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          
          $("#jam1").prop('checked', !$("#jam1").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
          $(this).addClass('active');
      });
      $('#btn2').click(function() {;
          $('#btn1').removeClass('active');
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          
          $("#jam2").prop('checked', !$("#jam2").is(':checked'));
          $("#jam1").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
          $(this).addClass('active');
      });
      $('#btn3').click(function() {
          $('#btn2').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam3").prop('checked', !$("#jam3").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam9").removeProp('checked');
          $('#btn10').removeClass('checked');
          $('#btn11').removeClass('checked');
          $('#btn12').removeClass('checked');
      });
      $('#btn4').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam4").prop('checked', !$("#jam4").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
      });
      $('#btn5').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam5").prop('checked', !$("#jam5").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
      });
      $('#btn6').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam6").prop('checked', !$("#jam6").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
      });
      $('#btn7').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam7").prop('checked', !$("#jam7").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
      });
      $('#btn8').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam8").prop('checked', !$("#jam8").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
      });
      $('#btn9').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam9").prop('checked', !$("#jam9").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
      });
      $('#btn10').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam10").prop('checked', !$("#jam10").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
      });
      $('#btn11').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam11").prop('checked', !$("#jam11").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam12").removeProp('checked');
          $("#jam13").removeProp('checked');
      });
      $('#btn12').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam12").prop('checked', !$("#jam12").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam13").removeProp('checked');
      });
      $('#btn13').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $(this).addClass('active');
          $("#jam13").prop('checked', !$("#jam13").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam1").removeProp('checked');
          $("#jam9").removeProp('checked');
          $("#jam11").removeProp('checked');
          $("#jam10").removeProp('checked');
          $("#jam12").removeProp('checked');
      });
      $('#clear').click(function() {
          $('#btn2').removeClass('active');
          $('#btn3').removeClass('active');
          $('#btn4').removeClass('active');
          $('#btn5').removeClass('active');
          $('#btn6').removeClass('active');
          $('#btn7').removeClass('active');
          $('#btn8').removeClass('active');
          $('#btn9').removeClass('active');
          $('#btn1').removeClass('active');
          $('#btn10').removeClass('active');
          $('#btn11').removeClass('active');
          $('#btn12').removeClass('active');
          $('#btn13').removeClass('active');
          $("#jam9").removeAttr('checked');
          $("#jam2").removeAttr('checked');
          $("#jam3").removeAttr('checked');
          $("#jam4").removeAttr('checked');
          $("#jam5").removeAttr('checked');
          $("#jam6").removeAttr('checked');
          $("#jam7").removeAttr('checked');
          $("#jam8").removeAttr('checked');
          $("#jam1").removeAttr('checked');
          $("#jam10").removeAttr('checked');
          $("#jam11").removeAttr('checked');
          $("#jam12").removeAttr('checked');
          $("#jam13").removeAttr('checked');
          $("#btnsubmit").prop('disabled',true);
      });
      
      // function clear(){
      //   $('.bekasi-kabupaten').show();
      //   $('.bekasi-kota').hide();
      //   $('#clear').click(function() {
      //       $('#btn2').removeClass('active');
      //       $('#btn3').removeClass('active');
      //       $('#btn4').removeClass('active');
      //       $('#btn5').removeClass('active');
      //       $('#btn6').removeClass('active');
      //       $('#btn7').removeClass('active');
      //       $('#btn8').removeClass('active');
      //       $('#btn9').removeClass('active');
      //       $('#btn1').removeClass('active');
      //       $("#jam9").removeAttr('checked');
      //       $("#jam2").removeAttr('checked');
      //       $("#jam3").removeAttr('checked');
      //       $("#jam4").removeAttr('checked');
      //       $("#jam5").removeAttr('checked');
      //       $("#jam6").removeAttr('checked');
      //       $("#jam7").removeAttr('checked');
      //       $("#jam8").removeAttr('checked');
      //       $("#jam1").removeAttr('checked');
      //       $("#btnsubmit").prop('disabled',true);
      //   });
      // }
      // ////////////////////////////
      $('#btnkartini1').click(function() {
          $('#btnkartini1').addClass('active');
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');
          
          $("#jamkartini1").prop('checked', !$("#jamkartini1").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');
          $(this).addClass('active');
      });
      $('#btnkartini2').click(function() {;
          $('#btnkartini1').removeClass('active');
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');
          
          $("#jamkartini2").prop('checked', !$("#jamkartini2").is(':checked'));
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');
          $(this).addClass('active');
      });
      $('#btnkartini3').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');

          $(this).addClass('active');
          $("#jamkartini3").prop('checked', !$("#jamkartini3").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $("#jamkartini13").removeProp('checked');
      });
      $('#btnkartini4').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');
          $(this).addClass('active');
          $("#jamkartini4").prop('checked', !$("#jamkartini4").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');
      });
      $('#btnkartini5').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');
          $(this).addClass('active');
          $("#jamkartini5").prop('checked', !$("#jamkartini5").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');

      });
      $('#btnkartini6').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');

          $(this).addClass('active');
          $("#jamkartini6").prop('checked', !$("#jamkartini6").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');
      });
      $('#btnkartini7').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');

          $(this).addClass('active');
          $("#jamkartini7").prop('checked', !$("#jamkartini7").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');
      });
      $('#btnkartini8').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');

          $(this).addClass('active');
          $("#jamkartini8").prop('checked', !$("#jamkartini8").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');
      });
      $('#btnkartini9').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');

          $(this).addClass('active');
          $("#jamkartini9").prop('checked', !$("#jamkartini9").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');
      });
      $('#btnkartini10').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');

          $(this).addClass('active');
          $("#jamkartini10").prop('checked', !$("#jamkartini10").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');
      });
      $('#btnkartini11').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');

          $(this).addClass('active');
          $("#jamkartini11").prop('checked', !$("#jamkartini11").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          $("#jamkartini13").removeProp('checked');

      });
      $('#btnkartini12').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');

          $(this).addClass('active');
          $("#jamkartini12").prop('checked', !$("#jamkartini12").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini13").removeProp('checked');

      });
      $('#btnkartini13').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');

          $(this).addClass('active');
          $("#jamkartini13").prop('checked', !$("#jamkartini13").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
          $("#jamkartini11").removeProp('checked');
          $("#jamkartini10").removeProp('checked');
          $("#jamkartini12").removeProp('checked');
          
      });
      $('#clearkartini').click(function() {
          $('#btnkartini2').removeClass('active');
          $('#btnkartini3').removeClass('active');
          $('#btnkartini4').removeClass('active');
          $('#btnkartini5').removeClass('active');
          $('#btnkartini6').removeClass('active');
          $('#btnkartini7').removeClass('active');
          $('#btnkartini8').removeClass('active');
          $('#btnkartini9').removeClass('active');
          $('#btnkartini1').removeClass('active');
          $('#btnkartini10').removeClass('active');
          $('#btnkartini11').removeClass('active');
          $('#btnkartini12').removeClass('active');
          $('#btnkartini13').removeClass('active');
          $("#jamkartini9").removeAttr('checked');
          $("#jamkartini2").removeAttr('checked');
          $("#jamkartini3").removeAttr('checked');
          $("#jamkartini4").removeAttr('checked');
          $("#jamkartini5").removeAttr('checked');
          $("#jamkartini6").removeAttr('checked');
          $("#jamkartini7").removeAttr('checked');
          $("#jamkartini8").removeAttr('checked');
          $("#jamkartini1").removeAttr('checked');
          $("#jamkartini10").removeAttr('checked');
          $("#jamkartini11").removeAttr('checked');
          $("#jamkartini12").removeAttr('checked');
          $("#jamkartini13").removeProp('checked');
          $("#btnkartinisubmit").prop('disabled',true);
      });
      
      // function clearkartini(){
      //   $('.bekasi-kabupaten').hide();
      //   $('.bekasi-kota').show();
      //   $('#clearkartini').click(function() {
      //       $('#btnkartini2').removeClass('active');
      //       $('#btnkartini3').removeClass('active');
      //       $('#btnkartini4').removeClass('active');
      //       $('#btnkartini5').removeClass('active');
      //       $('#btnkartini6').removeClass('active');
      //       $('#btnkartini7').removeClass('active');
      //       $('#btnkartini8').removeClass('active');
      //       $('#btnkartini9').removeClass('active');
      //       $('#btnkartini1').removeClass('active');
      //       $("#jamkartini9").removeAttr('checked');
      //       $("#jamkartini2").removeAttr('checked');
      //       $("#jamkartini3").removeAttr('checked');
      //       $("#jamkartini4").removeAttr('checked');
      //       $("#jamkartini5").removeAttr('checked');
      //       $("#jamkartini6").removeAttr('checked');
      //       $("#jamkartini7").removeAttr('checked');
      //       $("#jamkartini8").removeAttr('checked');
      //       $("#jamkartini1").removeAttr('checked');
      //       $("#btnkartinisubmit").prop('disabled',true);
      //   });
      // }
  </script>
</body>
</html>