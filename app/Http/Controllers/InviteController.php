<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class InviteController extends Controller
{
    public function index()
    {
        $rows=DB::table('registration_invites')->leftJoin('users','registration_invites.user_id','=','users.uid')->select('registration_invites.*','users.nickname')->orderByDesc('created_at')->orderBy('hash')->paginate(50);
        foreach($rows as $row){$row->code=$row->code_encrypted?decrypt($row->code_encrypted):'旧邀请码（查看桌面文件）';}
        return response()->view('admin.invites',['invites'=>$rows,'total'=>DB::table('registration_invites')->count(),'available'=>DB::table('registration_invites')->whereNull('used_at')->whereNull('revoked_at')->count()])->header('Cache-Control','no-store');
    }
    public function generate(Request $request)
    {
        $data=$request->validate(['count'=>'required|integer|min:1|max:100']);
        DB::transaction(function()use($data){for($i=0;$i<$data['count'];$i++){$code='ALEC-'.strtoupper(bin2hex(random_bytes(8)));DB::table('registration_invites')->insert(['hash'=>hash('sha256',$code),'code_encrypted'=>encrypt($code),'created_at'=>date('Y-m-d H:i:s')]);}});
        return redirect('/admin/invites')->with('invite_notice','已生成 '.$data['count'].' 个一次性邀请码。');
    }
    public function revoke(Request $request)
    {
        $data=$request->validate(['hash'=>'required|string|size:64']);
        $n=DB::table('registration_invites')->where('hash',$data['hash'])->whereNull('used_at')->whereNull('revoked_at')->update(['revoked_at'=>date('Y-m-d H:i:s')]);
        return redirect('/admin/invites')->with('invite_notice',$n?'邀请码已作废。':'未更改：邀请码已使用、已作废或不存在。');
    }
}
