<?php
namespace App\Http\Controllers;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use illuminate\Support\Facades\Hash;
use illuminate\Support\carbon;

class PeminjamanController extends Controller
{
    public function getpeminjaman(Request $req, $id)
    {
       $data_peminjaman=Peminjaman::
         join('siswa','siswa.id_siswa','=','peminjaman.id_siswa')
       ->join('kelas','kelas.id_kelas','=','peminjaman.id_kelas')
       ->join('buku','buku.id_buku','=','peminjaman.id_buku')
       ->orderBy('id_peminjaman', 'desc')
      ->get();
      return Response()->json($data_peminjaman);
    }

    public function getpeminjaman1(Request $req)
    {
        $data_peminjaman1=Peminjaman::
        join('siswa','siswa.id_siswa','=','peminjaman.id_siswa')
        ->join('kelas','kelas.id_kelas','=','peminjaman.id_kelas')
        ->join('buku','buku.id_buku','=','peminjaman.id_buku')
        ->get();
      return Response()->json($data_peminjaman1);
    }
    

    public function createpeminjaman(Request $req)
    {
        $validator = Validator::make($req->all(),[
            'id_siswa'=>'required',
            'id_kelas'=>'required',
            'id_buku'=>'required',
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson());
        }
        $tenggat = carbon::now()->addDays(4);
        $save = peminjaman::create([
            'id_siswa' =>$req->get('id_siswa'),
            'id_kelas' =>$req->get('id_kelas'),
            'id_buku' =>$req->get('id_buku'),
            'tgl_peminjaman' =>date('Y-m-d H:i:s'),
            'tenggat' =>$tenggat,
            'status' => 'Dipinjam',
        ]);
        if($save){
            return Response()->json(['status'=>true,'message' => 'Sukses Menambah Peminjaman']);
        } else {
            return Response()->json(['status'=>false,'message' => 'Gagal Menambah Peminjaman']);
        }
    }
    public function updatepeminjaman(Request $req,$id)
    {
        $validator = Validator::make($req->all(),[
            'id_siswa'=>'required',
            'id_kelas'=>'required',
            'id_buku'=>'required',

        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson());
        }
        $ubah = peminjaman::where('id_peminjaman',$id)->update([
            'id_siswa'    =>$req->get('id_siswa'),
            'id_kelas' =>$req->get('id_kelas'),
            'id_buku' =>$req->get('id_buku'),

        ]);
        if($ubah){
            return Response()->json(['status'=>true, 'message' => 'Sukses mengubah Peminjaman']);
        }else {
            return Response()->json(['status'=>false, 'message' => 'Gagal mengubah Peminjaman']);
}
    }
public function deletepeminjaman($id){
    $hapus=Peminjaman::where('id_peminjaman',$id)->delete();
    if($hapus){
        return Response()->json(['status'=>true,'message' => 'Sukses menghapus Data Peminjaman']);
    } else {
        return Response()->json(['status'=>false, 'message' => 'Gagal menghapus Data Peminjaman']);
    }
    }
public function kembalipeminjaman($id){
    $tgl_kembali = Carbon::now();
    $hapus=peminjaman::where('id_peminjaman',"=",$id)
    ->update([
        'status' => 'Kembali',
        'tgl_kembali' => $tgl_kembali
    ]);
    if($hapus){
        return Response()->json(['status'=>true,'message' => 'Sukses Mengembalikan buku ']);
    } else {
        return Response()->json(['status'=>false,'message' => 'Gagal Mengembalikan buku ']);
    }
}
}