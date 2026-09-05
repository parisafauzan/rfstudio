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
    $ambildatastudioKot = mysqli_query($koneksi,"SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE tanggal LIKE '%$tanggalkartini%' AND studio = 'Bekasi-Kota'   
                                                  UNION 
                                                  SELECT * FROM data_booking_cobahampirfinishjuga WHERE tanggal LIKE '%$tanggalkartini%' AND studio = 'Bekasi-Kota';");
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
                    $ambiljamkab1 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('08:00-09:00','08:00-09:00 09:30-10:30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('08:00-09:00','08:00-09:00 09:30-10:30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab1 = mysqli_fetch_array($ambiljamkab1);
                    if($jamkab1){
                    ?>
                    <button id="btn1" type="button" class="btn btn-outline-dark "  style="font-size:12px;" disabled>08:00-09:00</button>
                    <input id="jam1"  type="radio" hidden name="jampost" class="radio" value="08:00-09:00" disabled/>
                    <?php }else{?>
                      <button id="btn1" type="button" class="btn btn-outline-dark "  style="font-size:12px;">08:00-09:00</button>
                      <input id="jam1"  type="radio" hidden name="jampost" class="radio" value="08:00-09:00" />
                    <?php } ?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab2 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('09:30-10:30','08:00-09:00 09:30-10:30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('09:30-10:30','08:00-09:00 09:30-10:30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab2 = mysqli_fetch_array($ambiljamkab2);
                    if($jamkab2){
                    ?>
                      <button id="btn2" type="button" class="btn btn-outline-dark"  style="font-size:12px;" disabled>09:30-10:30</button>
                      <input id="jam2"  type="radio" hidden name="jampost" class="radio" value="09:30-10:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btn2" type="button" class="btn btn-outline-dark"  style="font-size:12px;">09:30-10:30</button>
                    <input id="jam2"  type="radio" hidden name="jampost" class="radio" value="09:30-10:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab3 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('11:00-12:00','11:00-12:00 12:30-13:30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('11:00-12:00','11:00-12:00 12:30-13:30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab3 = mysqli_fetch_array($ambiljamkab3);
                    if($jamkab3){
                    ?>
                    <button id="btn3" type="button" class="btn btn-outline-dark"  style="font-size:12px;" disabled>11:00-12:00</button>
                    <input id="jam3"  type="radio" hidden name="jampost" class="radio" value="11:00-12:00" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btn3" type="button" class="btn btn-outline-dark"  style="font-size:12px;">11:00-12:00</button>
                    <input id="jam3"  type="radio" hidden name="jampost" class="radio" value="11:00-12:00"/>
                    <?php } ?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab4 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('12:30-13:30','11:00-12:00 12:30-13:30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('11:00-12:00','11:00-12:00 12:30-13:30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab4 = mysqli_fetch_array($ambiljamkab4);
                    if($jamkab4){
                    ?>
                      <button id="btn4" type="button" class="btn btn-outline-dark"  style="font-size:12px;" disabled>12:30-13:30</button>
                      <input id="jam4"  type="radio" hidden name="jampost" class="radio" value="12:30-13:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btn4" type="button" class="btn btn-outline-dark"  style="font-size:12px;">12:30-13:30</button>
                    <input id="jam4"  type="radio" hidden name="jampost" class="radio" value="12:30-13:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab5 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('14:00-15:00','14:00-15:00 15:30-16:30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('14:00-15:00','14:00-15:00 15:30-16:30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab5 = mysqli_fetch_array($ambiljamkab5);
                    if($jamkab5){
                    ?>
                    <button id="btn5" type="button" class="btn btn-outline-dark"  style="font-size:12px;" disabled>14:00-15:00</button>
                    <input id="jam5"  type="radio" hidden name="jampost" class="radio" value="14:00-15:00" disabled/>
                    <?php 
                    }else{?>
                    <button id="btn5" type="button" class="btn btn-outline-dark"  style="font-size:12px;">14:00-15:00</button>
                    <input id="jam5"  type="radio" hidden name="jampost" class="radio" value="14:00-15:00"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab5 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('15:30-16:30','14:00-15:00 15:30-16:30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('15:30-16:30','14:00-15:00 15:30-16:30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab5 = mysqli_fetch_array($ambiljamkab5);
                    if($jamkab5){
                    ?>
                      <button id="btn6" type="button" class="btn btn-outline-dark"  style="font-size:12px;" disabled>15:30-16:30</button>
                      <input id="jam6"  type="radio" hidden name="jampost" class="radio" value="15:30-16:30" disabled/>
                      <?php }else{?>
                    <button id="btn6" type="button" class="btn btn-outline-dark"  style="font-size:12px;">15:30-16:30</button>
                    <input id="jam6"  type="radio" hidden name="jampost" class="radio" value="15:30-16:30"/>
                    <?php }?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2" >
                    <?php
                    $ambiljamkab7 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('17:00-18:00','17:00-18:00 18:30-19:30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('17:00-18:00','17:00-18:00 18:30-19:30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab7 = mysqli_fetch_array($ambiljamkab7);
                    if($jamkab7){
                    ?>
                      <button id="btn7" type="button" class="btn btn-outline-dark"  style="font-size:12px;" disabled>17:00-18:00</button>
                      <input id="jam7"  type="radio" hidden name="jampost" class="radio" value="17:00-18:00" disabled/>
                      <?php }else{?>
                      <button id="btn7" type="button" class="btn btn-outline-dark"  style="font-size:12px;">17:00-18:00</button>
                      <input id="jam7"  type="radio" hidden name="jampost" class="radio" value="17:00-18:00"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab8 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('18:30-19:30','17:00-18:00 18:30-19:30') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('18:30-19:30','17:00-18:00 18:30-19:30') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab8 = mysqli_fetch_array($ambiljamkab8);
                    if($jamkab8){
                    ?>
                      <button id="btn8" type="button" class="btn btn-outline-dark"  style="font-size:12px;" disabled>18:30-19:30</button>
                      <input id="jam8"  type="radio" hidden name="jampost" class="radio" value="18:30-19:30" disabled/>
                    <?php }else{?>
                      <button id="btn8" type="button" class="btn btn-outline-dark"  style="font-size:12px;">18:30-19:30</button>
                      <input id="jam8"  type="radio" hidden name="jampost" class="radio" value="18:30-19:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkab9 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('20:00-21:00','20:00-21:00 21:00-22:00') AND studio IN ('Bekasi-Kabupaten','') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggal' AND jam IN ('20:00-21:00','20:00-21:00 21:00-22:00') AND studio IN ('Bekasi-Kabupaten','');");
                    $jamkab9 = mysqli_fetch_array($ambiljamkab9);
                    if($jamkab9){
                    ?>
                      <button id="btn9" type="button" class="btn btn-outline-dark"  style="font-size:12px;" disabled>20:00-21:00</button>
                      <input id="jam9"  type="radio" hidden name="jampost" class="radio" value="20:00-21:00" disabled/>  
                    <?php }else{?>
                      <button id="btn9" type="button" class="btn btn-outline-dark"  style="font-size:12px;">20:00-21:00</button>
                      <input id="jam9"  type="radio" hidden name="jampost" class="radio" value="20:00-21:00"/>
                      <?php }?>
                  </span>
                </div>
                <input type="text" name="uniqid" hidden class="radio" value="<?php $random = random_bytes(3); $uniqid = (bin2hex($random));echo $uniqid;?>"/>
                <div class="text-center">
                  <button id="clear" type="button" class="btn btn-outline-danger"  style="font-size:12px;"><i class="fa-solid fa-trash-can"></i> clear</button>
                </div>
                </div> 
                <div class="text-center">
                    <button id="btnsubmit" type="submit" name="submit" class="btn btn-dark my-auto mt-3" style="width:fit-content;border-radius:0px;font-size:14px"> Oke lanjut > </button>
                  </div>                       
            </div>
          <?php          
        }
        else{
          ?>
          <div class="elem-group mx-auto inlined ms-2 me-2 mt-2 mb-2">
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                      <button id="btn1" type="button" class="btn btn-outline-dark "  style="font-size:12px;">08:00-09:00</button>
                      <input id="jam1"  type="radio" hidden name="jampost" class="radio" value="08:00-09:00" />
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btn2" type="button" class="btn btn-outline-dark"  style="font-size:12px;">09:30-10:30</button>
                    <input id="jam2"  type="radio" hidden name="jampost" class="radio" value="09:30-10:30"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btn3" type="button" class="btn btn-outline-dark"  style="font-size:12px;">11:00-12:00</button>
                    <input id="jam3"  type="radio" hidden name="jampost" class="radio" value="11:00-12:00"/> 
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <button id="btn4" type="button" class="btn btn-outline-dark"  style="font-size:12px;">12:30-13:30</button>
                    <input id="jam4"  type="radio" hidden name="jampost" class="radio" value="12:30-13:30"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btn5" type="button" class="btn btn-outline-dark"  style="font-size:12px;">14:00-15:00</button>
                    <input id="jam5"  type="radio" hidden name="jampost" class="radio" value="14:00-15:00"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btn6" type="button" class="btn btn-outline-dark"  style="font-size:12px;">15:30-16:30</button>
                    <input id="jam6"  type="radio" hidden name="jampost" class="radio" value="15:30-16:30"/>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2" >
                      <button id="btn7" type="button" class="btn btn-outline-dark"  style="font-size:12px;">17:00-18:00</button>
                      <input id="jam7"  type="radio" hidden name="jampost" class="radio" value="17:00-18:00"/>     
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btn8" type="button" class="btn btn-outline-dark"  style="font-size:12px;">18:30-19:30</button>
                      <input id="jam8"  type="radio" hidden name="jampost" class="radio" value="18:30-19:30"/>                   
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btn9" type="button" class="btn btn-outline-dark"  style="font-size:12px;">20:00-21:00</button>
                      <input id="jam9"  type="radio" hidden name="jampost" class="radio" value="20:00-21:00"/>
                  </span>
                </div>
                <input type="text" name="uniqid" hidden class="radio" value="<?php $random = random_bytes(3); $uniqid = (bin2hex($random));echo $uniqid;?>"/>
                <div class="text-center">
                  <button id="clear" type="button" class="btn btn-outline-danger" style="font-size:12px;"><i class="fa-solid fa-trash-can"></i> clear</button>
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
          // error_reporting(E_ALL);
          ?>
          <!--  -->
            <div class="elem-group mx-auto inlined ms-2 me-2 mt-2 mb-2 bekasi-kota">
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkota1 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('08:00-09:00','08:00-09:00 09:30-10:30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('08:00-09:00','08:00-09:00 09:30-10:30') AND studio IN ('Bekasi-Kota');");
                    $jamkota1 = mysqli_fetch_array($ambiljamkota1);
                    if($jamkota1){
                    ?>
                    <button id="btnkartini1" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;" disabled>08:00-09:00</button>
                    <input id="jamkartini1"  type="radio" hidden name="jampost" class="radio" value="08:00-09:00" disabled/>
                    <?php }else{?>
                      <button id="btnkartini1" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">08:00-09:00</button>
                      <input id="jamkartini1"  type="radio" hidden name="jampost" class="radio" value="08:00-09:00" />
                    <?php } ?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkota2 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('09:30-10:30','08:00-09:00 09:30-10:30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('09:30-10:30','08:00-09:00 09:30-10:30') AND studio IN ('Bekasi-Kota');");
                    $jamkota2 = mysqli_fetch_array($ambiljamkota2);
                    if($jamkota2){
                    ?>
                      <button id="btnkartini2" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;" disabled>09:30-10:30</button>
                      <input id="jamkartini2"  type="radio" hidden name="jampost" class="radio" value="09:30-10:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btnkartini2" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">09:30-10:30</button>
                    <input id="jamkartini2"  type="radio" hidden name="jampost" class="radio" value="09:30-10:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkota3 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('11:00-12:00','11:00-12:00 12:30-13:30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('11:00-12:00','11:00-12:00 12:30-13:30') AND studio IN ('Bekasi-Kota');");
                    $jamkota3 = mysqli_fetch_array($ambiljamkota3);
                    if($jamkota3){
                    ?>
                    <button id="btnkartini3" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;" disabled>11:00-12:00</button>
                    <input id="jamkartini3"  type="radio" hidden name="jampost" class="radio" value="11:00-12:00" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btnkartini3" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">11:00-12:00</button>
                    <input id="jamkartini3"  type="radio" hidden name="jampost" class="radio" value="11:00-12:00"/>
                    <?php } ?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkota4 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('12:30-13:30','11:00-12:00 12:30-13:30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('12:30-13:30','11:00-12:00 12:30-13:30') AND studio IN ('Bekasi-Kota');");
                    $jamkota4 = mysqli_fetch_array($ambiljamkota4);
                    if($jamkota4){
                    ?>
                      <button id="btnkartini4" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;" disabled>12:30-13:30</button>
                      <input id="jamkartini4"  type="radio" hidden name="jampost" class="radio" value="12:30-13:30" disabled/>
                      <?php
                    }else{
                    ?>
                    <button id="btnkartini4" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">12:30-13:30</button>
                    <input id="jamkartini4"  type="radio" hidden name="jampost" class="radio" value="12:30-13:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkota5 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('14:00-15:00','14:00-15:00 15:30-16:30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('14:00-15:00','14:00-15:00 15:30-16:30') AND studio IN ('Bekasi-Kota');");
                    $jamkota5 = mysqli_fetch_array($ambiljamkota5);
                    if($jamkota5){
                    ?>
                    <button id="btnkartini5" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;" disabled>14:00-15:00</button>
                    <input id="jamkartini5"  type="radio" hidden name="jampost" class="radio" value="14:00-15:00" disabled/>
                    <?php 
                  }else{?>
                    <button id="btnkartini5" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">14:00-15:00</button>
                    <input id="jamkartini5"  type="radio" hidden name="jampost" class="radio" value="14:00-15:00"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkota5 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('15:30-16:30','14:00-15:00 15:30-16:30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('15:30-16:30','14:00-15:00 15:30-16:30') AND studio IN ('Bekasi-Kota');");
                    $jamkota5 = mysqli_fetch_array($ambiljamkota5);
                    if($jamkota5){
                    ?>
                      <button id="btnkartini6" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;" disabled>15:30-16:30</button>
                      <input id="jamkartini6"  type="radio" hidden name="jampost" class="radio" value="15:30-16:30" disabled/>
                      <?php }else{?>
                    <button id="btnkartini6" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">15:30-16:30</button>
                    <input id="jamkartini6"  type="radio" hidden name="jampost" class="radio" value="15:30-16:30"/>
                    <?php }?>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2" >
                    <?php
                    $ambiljamkota7 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('17:00-18:00','17:00-18:00 18:30-19:30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('17:00-18:00','17:00-18:00 18:30-19:30') AND studio IN ('Bekasi-Kota');");
                    $jamkota7 = mysqli_fetch_array($ambiljamkota7);
                    if($jamkota7){
                    ?>
                      <button id="btnkartini7" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;" disabled>17:00-18:00</button>
                      <input id="jamkartini7"  type="radio" hidden name="jampost" class="radio" value="17:00-18:00" disabled/>
                      <?php }else{?>
                      <button id="btnkartini7" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">17:00-18:00</button>
                      <input id="jamkartini7"  type="radio" hidden name="jampost" class="radio" value="17:00-18:00"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkota8 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('18:30-19:30','17:00-18:00 18:30-19:30') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('18:30-19:30','17:00-18:00 18:30-19:30') AND studio IN ('Bekasi-Kota');");
                    $jamkota8 = mysqli_fetch_array($ambiljamkota8);
                    if($jamkota8){
                    ?>
                      <button id="btnkartini8" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;" disabled>18:30-19:30</button>
                      <input id="jamkartini8"  type="radio" hidden name="jampost" class="radio" value="18:30-19:30" disabled/>
                    <?php }else{?>
                      <button id="btnkartini8" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">18:30-19:30</button>
                      <input id="jamkartini8"  type="radio" hidden name="jampost" class="radio" value="18:30-19:30"/>
                    <?php }?>
                  </span>
                  <span class="button-radio mx-2">
                    <?php
                    $ambiljamkota9 = mysqli_query($koneksi,"SELECT * FROM `data_konfirmasi_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('20:00-21:00','20:00-21:00 21:00-22:00') AND studio IN ('Bekasi-Kota') UNION SELECT * FROM `data_booking_cobahampirfinishjuga` WHERE tanggal = '$tanggalkartini' AND jam IN ('20:00-21:00','20:00-21:00 21:00-22:00') AND studio IN ('Bekasi-Kota');");
                    $jamkota9 = mysqli_fetch_array($ambiljamkota9);
                    if($jamkota9){
                    ?>
                      <button id="btnkartini9" type="button" class="btn btn-outline-dark"  style="font-size:12px;" disabled>20:00-21:00</button>
                      <input id="jamkartini9"  type="radio" hidden name="jampost" class="radio" value="20:00-21:00" disabled/>  
                    <?php }else{?>
                      <button id="btnkartini9" type="button" class="btn btn-outline-dark"  style="font-size:12px;">20:00-21:00</button>
                      <input id="jamkartini9"  type="radio" hidden name="jampost" class="radio" value="20:00-21:00"/>
                      <?php }?>
                  </span>
                </div>
                <input type="text" name="uniqid" hidden class="radio" value="<?php $random = random_bytes(3); $uniqid = (bin2hex($random));echo $uniqid;?>"/>
                <div class="text-center">
                  <button id="clearkartini" type="button" class="btn btn-outline-danger"  style="font-size:12px;"><i class="fa-solid fa-trash-can"></i> clear</button>
                </div>
                </div> 
                <div class="text-center">
                    <button id="btnkartinisubmit" type="submit" name="submit" class="btn btn-dark my-auto mt-3" style="width:fit-content;border-radius:0px;font-size:14px"> Oke lanjut > </button>
                  </div>                       
            </div>
          <?php          
        }
        else{
          ?>
          <div class="elem-group mx-auto inlined ms-2 me-2 mt-2 mb-2">
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                      <button id="btnkartini1" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">08:00-09:00</button>
                      <input id="jamkartini1"  type="radio" hidden name="jampost" class="radio" value="08:00-09:00" />
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btnkartini2" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">09:30-10:30</button>
                    <input id="jamkartini2"  type="radio" hidden name="jampost" class="radio" value="09:30-10:30"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btnkartini3" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">11:00-12:00</button>
                    <input id="jamkartini3"  type="radio" hidden name="jampost" class="radio" value="11:00-12:00"/> 
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2">
                    <button id="btnkartini4" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">12:30-13:30</button>
                    <input id="jamkartini4"  type="radio" hidden name="jampost" class="radio" value="12:30-13:30"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btnkartini5" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">14:00-15:00</button>
                    <input id="jamkartini5"  type="radio" hidden name="jampost" class="radio" value="14:00-15:00"/>
                  </span>
                  <span class="button-radio mx-2">
                    <button id="btnkartini6" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">15:30-16:30</button>
                    <input id="jamkartini6"  type="radio" hidden name="jampost" class="radio" value="15:30-16:30"/>
                  </span>
                </div>
                <div class="d-flex justify-content-center text-center my-2">
                  <span class="button-radio mx-2" >
                      <button id="btnkartini7" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">17:00-18:00</button>
                      <input id="jamkartini7"  type="radio" hidden name="jampost" class="radio" value="17:00-18:00"/>     
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btnkartini8" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">18:30-19:30</button>
                      <input id="jamkartini8"  type="radio" hidden name="jampost" class="radio" value="18:30-19:30"/>                   
                  </span>
                  <span class="button-radio mx-2">
                      <button id="btnkartini9" type="button" class="btn btn-outline-dark kartini"  style="font-size:12px;">20:00-21:00</button>
                      <input id="jamkartini9"  type="radio" hidden name="jampost" class="radio" value="20:00-21:00"/>
                  </span>
                </div>
                <input type="text" name="uniqid" hidden class="radio" value="<?php $random = random_bytes(3); $uniqid = (bin2hex($random));echo $uniqid;?>"/>
                <div class="text-center">
                  <button id="clearkartini" type="button" class="btn btn-outline-danger"  style="font-size:12px;"><i class="fa-solid fa-trash-can"></i> clear</button>
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
          
          $("#jam1").prop('checked', !$("#jam1").is(':checked'));
          $("#jam2").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam9").removeProp('checked');
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
          
          $("#jam2").prop('checked', !$("#jam2").is(':checked'));
          $("#jam1").removeProp('checked');
          $("#jam3").removeProp('checked');
          $("#jam4").removeProp('checked');
          $("#jam5").removeProp('checked');
          $("#jam6").removeProp('checked');
          $("#jam7").removeProp('checked');
          $("#jam8").removeProp('checked');
          $("#jam9").removeProp('checked');
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
          $("#jam9").removeAttr('checked');
          $("#jam2").removeAttr('checked');
          $("#jam3").removeAttr('checked');
          $("#jam4").removeAttr('checked');
          $("#jam5").removeAttr('checked');
          $("#jam6").removeAttr('checked');
          $("#jam7").removeAttr('checked');
          $("#jam8").removeAttr('checked');
          $("#jam1").removeAttr('checked');
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
          
          $("#jamkartini1").prop('checked', !$("#jamkartini1").is(':checked'));
          $("#jamkartini2").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
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
          
          $("#jamkartini2").prop('checked', !$("#jamkartini2").is(':checked'));
          $("#jamkartini1").removeProp('checked');
          $("#jamkartini3").removeProp('checked');
          $("#jamkartini4").removeProp('checked');
          $("#jamkartini5").removeProp('checked');
          $("#jamkartini6").removeProp('checked');
          $("#jamkartini7").removeProp('checked');
          $("#jamkartini8").removeProp('checked');
          $("#jamkartini9").removeProp('checked');
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
          $("#jamkartini9").removeAttr('checked');
          $("#jamkartini2").removeAttr('checked');
          $("#jamkartini3").removeAttr('checked');
          $("#jamkartini4").removeAttr('checked');
          $("#jamkartini5").removeAttr('checked');
          $("#jamkartini6").removeAttr('checked');
          $("#jamkartini7").removeAttr('checked');
          $("#jamkartini8").removeAttr('checked');
          $("#jamkartini1").removeAttr('checked');
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