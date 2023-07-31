<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\TipoProceso;
use App\Models\ControlBiometrico;
use App\Models\Preinscripcion;
use App\Models\Documento;
use App\Models\Estudiante;
use App\Models\AvancePostulante;
use App\Models\Paso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Fpdi;

class IngresoController extends Controller
{


    public function getDatosIngreso($dni){
        $res = DB::select("SELECT  
            postulante.id,
            postulante.nro_doc,
            postulante.nombres,
            postulante.primer_apellido,
            postulante.segundo_apellido,
            programa.nombre AS programa,
            procesos.nombre AS proceso,
            resultados.puntaje,
            resultados.puesto,
            resultados.puesto_general,
            resultados.fecha
            FROM resultados
            JOIN postulante ON postulante.nro_doc = resultados.dni_postulante
            JOIN inscripciones ON postulante.id = inscripciones.id_postulante
            JOIN programa ON programa.id = inscripciones.id_programa
            JOIN procesos ON procesos.id = inscripciones.id_proceso
            WHERE postulante.nro_doc = ".$dni);

        $this->response['estado'] = true;
        $this->response['datos'] = $res[0];
        return response()->json($this->response, 200);
    }

    public function biometrico(Request $request){

        $re = DB::select("SELECT
            procesos.anio, procesos.ciclo_oti,
            programa.programa_oti,
            postulante.primer_apellido AS paterno,
            postulante.segundo_apellido AS materno, postulante.nombres,
            tipo_documento_identidad.documento_oti AS tipo_doc_oti,
            postulante.nro_doc AS dni,
            users.name, users.paterno as upaterno,
            postulante.fec_nacimiento,
            postulante.sexo,
            postulante.ubigeo_residencia,
            postulante.direccion,
            postulante.estado_civil,
            resultados.fecha,
            postulante.email,
            postulante.celular,
            programa.cod_esp,
            modalidad.modalidad_oti,
            resultados.puntaje,
            resultados.puesto,
            resultados.puesto_general,
            postulante.id AS id_postulante,
            procesos.id AS id_proceso, procesos.nombre AS proceso,
            modalidad.id AS id_modalidad, modalidad.nombre AS modalidad,
            programa.nombre AS programa
            FROM resultados
            LEFT JOIN postulante ON resultados.dni_postulante =  postulante.nro_doc
            LEFT JOIN inscripciones ON inscripciones.id_postulante = postulante.id
            LEFT JOIN modalidad ON inscripciones.id_modalidad = modalidad.id
            LEFT JOIN procesos ON resultados.id_proceso = procesos.id 
            left join users on users.id = inscripciones.id_usuario
            LEFT JOIN programa ON programa.id = inscripciones.id_programa
            LEFT JOIN tipo_documento_identidad ON postulante.tipo_doc = tipo_documento_identidad.id
            WHERE resultados.apto = 'SI'
            AND resultados.dni_postulante = ".$request->dni." AND resultados.id_proceso = ". auth()->user()->id_proceso.";");

            $this->pdf($re[0]);
            //$this->pdfbiometrico($re[0]);
//          $this->UnirPDF($request->dni);

            $pdf = new Fpdi();
            
            $files = [
                public_path('/documentos/cepre2023-II/'.$request->dni.'/').'constancia-ingreso-1.pdf',
//                public_path('/documentos/cepre2023-II/'.$request->dni.'/').'control-biometrico-1.pdf'
            ];

            foreach ($files as $file) {
                $pageCount = $pdf->setSourceFile($file);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $template = $pdf->importPage($pageNo);
                    $pdf->AddPage();
                    $pdf->useTemplate($template);
                }
            }

            $outputFilePath = public_path('/documentos/cepre2023-II'.'/'.$request->dni.'/control-biometrico-unido.pdf');
            $pdf->Output($outputFilePath, 'F');

            try {
                DB::transaction(function () use ($request, $re) {

                    $database2 = 'mysql_secondary';
                    $rs = DB::connection($database2)->select("SELECT CONCAT('23', (max(right(e.num_mat,LENGTH(TRIM(e.num_mat))-2)+0) + 1)) AS siguiente FROM unapnet.estudiante e WHERE left(e.num_mat,2) = '23' ;");
                    $nuevoCodigo = $rs[0]->siguiente;

                    $biometric = ControlBiometrico::create([
                        'id_proceso' => $re[0]->id_proceso,
                        'id_postulante' => $re[0]->id_postulante,
                        'codigo_ingreso' => $nuevoCodigo,
                        'estado' => 1,
                        'id_usuario' => auth()->id()
                    ]);

                    $e_civil = 1;
                    if($re[0]->estado_civil == 1 ) { $e_civil = 2;}
                    if($re[0]->estado_civil == 2 ) { $e_civil = 1;}
                    if($re[0]->estado_civil == 3 ) { $e_civil = 3;}
                    if($re[0]->estado_civil == 4 ) { $e_civil = 6;}

                    $estudiante = Estudiante::on('mysql_secondary')->create([
                        'num_mat' => $nuevoCodigo,
                        'cod_car' => $re[0]->programa_oti,
                        'paterno' => $re[0]->paterno, 
                        'materno' => $re[0]->materno,
                        'nombres' => $re[0]->nombres,
                        'tip_doc' => $re[0]->tipo_doc_oti,
                        'num_doc' => $re[0]->dni,
                        'fch_nac' => $re[0]->fec_nacimiento,
                        'sexo' => $re[0]->sexo,
                        'ubigeo' => $re[0]->ubigeo_residencia,
                        'mod_ing' => $re[0]->modalidad_oti,
                        'est_civ' => $e_civil,
                        'fch_ing' => $re[0]->fecha,
                        'direc' => $re[0]->direccion,
                        'email' => $re[0]->email,
                        'con_est' => 5,
                        'celular' => $re[0]->celular,
                        'cod_esp' => $re[0]->cod_esp,
                        'puntaje' => $re[0]->puntaje,
                        'puesto_escuela' => $re[0]->puesto,
                        'puesto_general' => $re[0]->puesto_general,
                        'ano_ing' => $re[0]->anio,
                        'per_ing' => $re[0]->ciclo_oti

                    ]);

                    // $avancePostulante->avance = 5;  
                    // $avancePostulante = AvancePostulante::where('dni_postulante', $request->dni)->first();
                    // $avancePostulante->save();
                
                });
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                // Registrar el error en un archivo de registro
                \Log::error('Error en la transacción: ' . $errorMessage);
                // Devolver una respuesta de error al usuario con el mensaje de error
                return response()->json(['error' => 'Ocurrió un error en la transacción: ' . $errorMessage], 500);
        
        
            }
        


        $this->response['estado'] = true;
        $this->response['datos'] = $request->dni;
        return response()->json($this->response, 200);
        // return response()->download($outputFilePath);
        // return response()->download($outputFilePath)->deleteFileAfterSend();
    } 

    public function pdf($datos){
        setlocale(LC_TIME, 'es_ES.utf8'); 
        $date = Carbon::now()->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');
        $dateI = Carbon::createFromFormat('Y-m-d', $datos->fecha)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');
        #$dateI = "26 de Junio del 2034";
        $data = $datos;
        $pdf = Pdf::loadView('ingreso.constancia', compact('data','date','dateI'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();
        $rutaCarpeta = public_path('/documentos/cepre2023-II/'.$data->dni);
        if (!File::exists($rutaCarpeta)) { File::makeDirectory($rutaCarpeta, 0755, true, true); }
        file_put_contents(public_path('/documentos/cepre2023-II/'.$data->dni.'/').'constancia-ingreso-1.pdf', $output);
        return $pdf->stream();
    }

    public function pdfbiometrico($datos){

        $data = $datos->dni;
        $pdf = Pdf::loadView('ingreso.datosbiometricos', compact('data'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();
        $rutaCarpeta = public_path('/documentos/cepre2023-II/'.$datos->dni);
        if (!File::exists($rutaCarpeta)) {
            File::makeDirectory($rutaCarpeta, 0755, true, true);
        }
        file_put_contents(public_path('/documentos/cepre2023-II/'.$datos->dni.'/').'control-biometrico-1.pdf', $output);
        return $pdf->stream();
    }


    public function pdfbiometrico2($dni){

        $datos = DB::select(
            "SELECT
            procesos.nombre as proceso,
            postulante.primer_apellido AS paterno,
            postulante.segundo_apellido AS materno, postulante.nombres,
            tipo_documento_identidad.nombre,
            postulante.nro_doc AS dni,
            users.name, users.paterno as upaterno,
            modalidad.nombre as modalidad,
            resultados.fecha,
            resultados.puntaje,
            resultados.puesto,
            resultados.puesto_general,
            programa.nombre AS programa
            FROM resultados
            LEFT JOIN postulante ON resultados.dni_postulante =  postulante.nro_doc
            LEFT JOIN inscripciones ON inscripciones.id_postulante = postulante.id
            LEFT JOIN modalidad ON inscripciones.id_modalidad = modalidad.id
            LEFT JOIN procesos ON resultados.id_proceso = procesos.id 
            left join users on users.id = inscripciones.id_usuario
            LEFT JOIN programa ON programa.id = inscripciones.id_programa
            LEFT JOIN tipo_documento_identidad ON postulante.tipo_doc = tipo_documento_identidad.id
            WHERE resultados.apto = 'SI'
            AND resultados.dni_postulante = " .$dni. " AND resultados.id_proceso = 4"
        );

        $data = $datos[0];
        $hinsI = public_path('fotos/huella/').$dni.'.jpg';
        $hinsD = public_path('fotos/huella/').$dni.'x.jpg';
        $hexaI = public_path('hexamencepre/').$dni.'.jpg';
        $hexaD = public_path('hexamencepre/').$dni.'x.jpg';
        $hbioI = public_path('hbiometricocepre/').$dni.'.jpg';
        $hbioD = public_path('hbiometricocepre/').$dni.'x.jpg';
        $fins = public_path('fotos/inscripcion/').$dni.'.jpg';
        $fbio = public_path('fotos/biometrico/').$dni.'.jpg';

        setlocale(LC_TIME, 'es_ES.utf8');
        $fecha = $data->fecha;
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $fecha)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');

        $fec_imp = '2023-07-27';
        $fimp = \Carbon\Carbon::createFromFormat('Y-m-d', $fec_imp)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');
//        $fimp =  Carbon::now()->locale('es')->isoFormat('DD [de] MMMM [del] YYYY');

        $pdf = Pdf::loadView('ingreso.datosbiometricos', compact('data','hinsI','hinsD','hexaI','hexaD','hbioI','hbioD','fins','fbio','date', 'fimp'));
        $pdf->setPaper('A4', 'portrait');
        $output = $pdf->output();
        // $rutaCarpeta = public_path('/documentos/cepre2023-II/'.$datos->dni);
        // if (!File::exists($rutaCarpeta)) {
        //     File::makeDirectory($rutaCarpeta, 0755, true, true);
        // }
        file_put_contents(public_path('/documentos/ingresantescepre/').$dni.'.pdf', $output);
//        return $pdf->stream();
    } 

    public function UnirPDF($dni){

        $pdf = new Fpdi();
        
        $files = [
            public_path('/documentos/cepre2023-II/'.$dni.'/').'constancia-ingreso-1.pdf',
            public_path('/documentos/cepre2023-II/'.$dni.'/').'control-biometrico-1.pdf'
        ];

        foreach ($files as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $template = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($template);
            }
        }

        $outputFilePath = public_path('/documentos/cepre2023-II/'.'/'.$dni.'control-biometrico-unido.pdf');
        $pdf->Output($outputFilePath, 'F');

        return response()->download($outputFilePath);
        // return response()->download($outputFilePath)->deleteFileAfterSend();

    }


    public function constanciasIngreso() {
        // $ingresantes = [
        // '73584026','76550586','74735572','74028918','73445140','75721651','75022899','60758775','75844018','71525267','73475954','70513752','75270874','60732618','76944739',
        // '76641559','71128547','71804210','71953453','72280659','70383663','71349059','73813443','74375912','71923672','72073941','75876298','76553337','73380972','75782519',
        // '72577356','72629838','73199080','72298718','74705217','75585627','74231384','71720754','73416839','73694286','73104636','75791152','75828193','75667292','76039542',
        // '72326190','75259894','60764810','73619563','72396725','76481845','74762105','75605988','74838303','70689420','71348787','73818332','74161101','71526827','71893207',
        // '73361369','76057432','73653888','73263230','73299189','73624590','60780762','72083867','75277096','72269757','71378935','75218917','77822248','74808769','73331014',
        // '73952920','76576593','73867763','73266758','73776016','75484665','73523120','71267211','75764826','60458340','74421821','71824428','70446492','77141145','73814210',
        // '74614371','74884817','74966349','75803962','70864459','73643547','76301204','75218912','77239962','77019689','60066302','75333485','72005064','75386760','71576906',
        // '72153046','73643805','75471566','76196903','72948250','76907613','75396853','76201714','74046576','74161080','75884845','73638484','72912965','74357621','75464519',
        // ];
        // $ingresantes = [
        // '73254600','74878715','74038382','73636236','60417184','72113287','73645905','71497218','76039760','74056909','75982956','60175050','76694748','75001919','75761364',
        // '71931687','72519521','75097199','74629379','74309339','73102255','73003384','75115329','77080971','74420466','60178752','76860476','74949212','74939984','71945301',
        // '75348934','73712253','77067552','76770708','74292183','77415078','72088918','74238458','75580218','70211802','71508606','75348644','73636020','75061831','75936295',
        // '60174754','73648215','74543134','72735152','60471046','73763841','76929834','76953778','75918194','73417293','71561540','73701436','76650573','71666290','76194895',
        // '76186838','74059199','76009176','72190127','71724464','74449729','74575066','75842577','75837807','73091433','76281448','75248740','75101363','74697285','71840618'];

        // $ingresantes = [
        // '74956220','75007479','71104032','77246024','78289140','75141964','73818627','77694736','71958360','76932517','71459851','73380067','75814573','73272382','71864621',
        // '73345548','73775939','75498988','76735193','73331009','61766073','70866915','73517214','62681089','71121773','74497221','60765404','73384395','71783843','71864664',
        // '77339343','74880115','75833383','76608616','73537583','71864503','76249188','74225311','71958444','75433311','75921149','75706556','70452983','73771595','60732879',
        // '71508473','77685833','75393035','74080541','77086509','75944844','61650353','71440175','74382511','73744330','73759045','60170630','73770748','75676959','72577358',
        // '73750747','72093170','76969903','72883328','73525713','75697385','73298969','60758885','76008282','75536831','76869586','72577351','73714245','70497236','77022006',
        // '71627766','73472097','75125686','71621867','77694131','75950273','74809169','74157616','76970124','73390312','74449898','78197889','75847012','70413794','74658152',
        // '73523004','73768725','74526130','71743399','70201068','74598902','76223535','73097185','74600244','76749912','76954486','75078635','74659066','73382309','73182895'
        // ];

        // $ingresantes = [
        // '74984045','73647235','72228972','74705099','71958703','76069262','71721781','60758637','60759126','73313367','73311238','73812862','74154576','74348127','74560965',
        // '75564067','75832635','71754859','76858523','76269385','76639322','73024107','73742723','76173121','76827430','70921314','76731774','76173134','70184739','73541195',
        // '76228589','76919008','72245860','71531842','73181464','71864548','75523240','73311206','74686489','72535523','74808430','60422568','71947802','76137576','77923247',
        // '72073773','74358504','63428050','75509809','60199855','71348895','74878739','71267119','71921591','73818937','74761101','73801587','74238390','75348689','76962765',
        // '60758991','74888930','74838304','76507292','74536757','76007853','76008608','71995654','77915953','76046357','76081371','73820029','73773291','62363884','73613295',
        // '75894458','76737640','71525375','74467077','75386564','72554211','75342043','62791325','76235604','70989383','74031725','75348479','75342041','77567195','74230246',
        // '74155962','73810246','73643170','74575675','76941636','77022008','74314877','71122838','73258648','72093159','77430874','71754857','74378439','75362957','76432622'];

        // $ingresantes = [
        //     '74881471','70080948','75867315','60066068','73264832','73908189','75475225','75097209','60764933','76033008','76796723','74868781','72943615','60065710','74057067',
        //     '75925182','73649252','71700946','73355114','74757654','73821402','74497987','73968835','74432389','74142872','74280343','71522252','71460735','75314950','73818904',
        //     '73701147','73377999','76135000','71994871','74178835','77021999','77275457','73425461','73933948','75238916','77067081','71936374','76053662','63321650','73744259',
        //     '72471519','70184744','70831920','77674757','74246213','76338256','60422514','71044808','72039974','73312123','73522906','71970878','70316723','74984380','74583524',
        //     '75571717','71466425','75796275','71348706','73579136','73273953','73770245','71572494','73767920','73311208','74076789','73713283','71689827','76546319','71267137',
        //     '71265667','78203044','75402645','60067905','71348568','77224322','71778128','73649888','71461232','71953234','62718722','73855802','75721664','77021934','76428458',
        //     '74227871','74961204','76230016','75094653','74618809','73380968','60068082','73653196','74599785','76971120','60417120','76006906','76647594','74245213','72298813'];

        // $ingresantes = [
        //     '74843592','77695239','73763862','73773355','74474394','71130364','71449935','75565001','75699812','73384271','60199877','76623029','76903824','72763940','77246815',
        //     '74949206','76903825','72177375','77681320','73581930','60837166','75082259','74581232','77660379','73819326','73111598','75209171','77475249','73055881','60422498',
        //     '73744949','60180230','75090610','75165974','71107742','74245195','75894083','73707909','74249180','74939936','60758990','72437700','75930038','73385765','75340624',
        //     '72925953','77277945','74802320','61204979','71348379','73522722','75543730','75827426','73760079','74430785','71531556','75709284','73256964','71522577','75150274',
        //     '76771354','73311587','71792356','75388079','75873201','75676871','60417077','74624426','73311230','75686074','71388806','75837803','71995629','76988822','75506458',
        //     '74759871','74373597','71262193','73517272','60850563','70350457','72656101','74043604','77043969','73740457','76683179','75272939','70415938','77706313','75981179',
        //     '75928872','75233573','73890716','75096969','76910237','60069675','74836248','75517851','73522925','75926734','74994154','71547572','71503734','75314292','73764119'
        // ];

        $ingresantes = [
            '75949464','73648242','76869589','73760156','71882093','76923760','60068006','73313344','73313386','48308088','75965470','72792210','73965096','76972826','60909401',
            '72640765','76660303','73209343','71090212','71348912','75208812','76344160','73810924','70279898','72752975','73581633','70501279','71837723','75348312','73745236',
            '72107812','73941219','77094493','70811723','76735420','62718804','71655059','76039747','73773883','75938834','75998725','76021402','75395748','62301205','73384201',
            '71778991','70547400','78286976','70821450','75929956','72368927','75379199','72487716','73252438','75475224','76799227','76944244','76186573','73810914','76217044',
            '76909568','75521468','75944981','72073904','74034602','73309889','73743041','74440565','76701138','73993151','74808429','72326673','72086307','74633604','77238883',
            '71500890','74725077','73254620','71374909','73392461','76536994','75701685','71461199','60066407','72073935','73645441','60732829','70657793','74724088','75714420',
            '76737843','70830333','71945084','71348617','60764923','73197232','73266609','71025450','75466128','75482849'
        ];
            
        foreach ($ingresantes as $dni) {

            $this->pdfbiometrico2($dni);
        }
    
    
    }





}
