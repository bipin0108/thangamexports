<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;
use App\Events\UserRegisteredEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = User::where('is_admin','0')->latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn() 
                    ->addColumn('action', function($data){
                        $button = '<a href="'.route('user.edit', $data->id).'" type="button" class="btn btn-sm btn-primary" ><i class="fas fa-pencil-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" id="'.$data->id.'" class="btn btn-sm btn-danger delete" ><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })->rawColumns(['action'])->make(true);
        }
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users,email', 
            'password' => 'required|string|min:6',
            'mobile' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        /*$image = $request->file('image');
        $imagename = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images/user');
        $image->move($destinationPath, $imagename);*/
        $imagename = '';
        if(!empty($request->file('image'))){

            $file = $request->file('image');
            $imagename = $file->store('user', 's3');
        }

        // $to_name = "Bipin Nakrani";
        // $to_email = "mkrupali2806@gmail.com";

        // $data = array('name'=>$to_name,'body'=>'Test Mail');

        // Mail::send('emails.welcome', $data, function($message) use ($to_name, $to_email){
        //     $message->to($to_email)->subject('Lara mail subject.');
        // });

        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name'); 
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->mobile = $request->input('mobile');
        $user->profile_image = $imagename;
        $user->save(); 

        // $user->password = $request->input('password');
        // event(new UserRegisteredEvent($user));


        $from = "nakrani0108@gmail.com";
        $to = $request->input('email');
        $subject = env('APP_NAME', 'Thangam Exports').' forgot password';

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
         
        // Create email headers
        $headers .= 'From: '.env('APP_NAME', 'Thangam Exports').' <'.$from.'>'."\r\n".
            'Reply-To: '.env('APP_NAME', 'Thangam Exports').' <'.$from.'>'."\r\n" .
            'X-Mailer: PHP/' . phpversion();
        
        $message  = "-------------------------------------------<br/>";
        $message .= " thangamexports.com : Forgot Password <br/>";
        $message .= "-------------------------------------------<br/><br/>";
        
        $message .= "Dear ".$request->input('first_name')." ".$request->input('first_name')." <br/><br/>";
        
        $message .= "Please check your password of your ".env('APP_NAME', 'Thangam Exports')." account,<br/><br/>";
        
        $message .= "For your information,<br/>";
        $message .= "<b>Email:</b> ".$request->input('email')."<br/>";
        $message .= "<b>Password:</b> ".$request->input('password')."<br/><br/>";

        $message .= "Sincerely,<br/>";
        $message .= env('APP_NAME', 'Thangam Exports')." Team<br/>";
        $message .= "https://thangamexports.com";

        mail($to,$subject,$message, $headers);
        
        return redirect()->route('user.index')
                        ->with('success','User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sample_data  $sample_data
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sample_data  $sample_data
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id','=',$id)->first();
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sample_data  $sample_data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users,email,'.$id,  
            'mobile' => 'required|numeric', 
        ]);

        $user = User::where('id',$id)->first();  
        $imagename = $user->image;
        if(!empty($request->file('image'))){

            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }
            $file = $request->file('image');
            $imagename = $file->store('user', 's3');
            
            /*$file = $res->image;
            if(!empty($file)){
                $filename = public_path().'/images/category/'.$file;
                \File::delete($filename);
            }
            $image = $request->file('image');
            $imagename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/category');
            $image->move($destinationPath, $imagename);*/
        }
        
        $affectedRows = User::where('id', $id)
                    ->update(array(
                        'first_name' => $request->input('first_name'),
                        'last_name' => $request->input('last_name'), 
                        'email' => $request->input('email'),
                        'mobile' => $request->input('mobile'), 
                        'profile_image' => !empty($imagename)?$imagename:'', 
                    ));

        return redirect()->route('user.index')
                            ->with('success','User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sample_data  $sample_data
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        $imagename = $user->profile_image;
        if(!empty($imagename)){
            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }  
            /*$filename = public_path().'/images/user/'.$file;
            \File::delete($filename);*/
        }
        User::where('id',$id)->delete();
        return redirect()->route('user.index')
                ->with('success','User deleted successfully.');
    }
}
