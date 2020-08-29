<?php

namespace Bulkly\Http\Controllers;

use Bulkly\BufferPosting;
use Bulkly\SocialPostGroups;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $histories = BufferPosting::all();
        $types = SocialPostGroups::select('type')->groupBy('type')->get();
        $result = [];
        $output = [];
        if (isset($_GET['date'])){
            if (!empty($_GET['date'])){
                $histories = BufferPosting::whereDate('sent_at',$_GET['date'])->get();
            }
        }

        foreach($histories as $key=>$history) {
            $color = '#6dc993';
            if($history->account->type=='twitter'){
                $color = '#00acee';
            } else if($history->account->type=='facebook'){
                $color = '#3b5998';
            } else if($history->account->type=='instagram'){
                $color = '#8134af';
            } else if($history->account->type=='linkedin'){
                $color = '#0e76a8';
            } else if($history->account->type=='google'){
                $color = '#3cba54';
            }
            $datetime = new \DateTime($history->sent_at, new \DateTimeZone('America/Chicago'));
            if(isset($_GET['group_type'])){
                if(!empty($_GET['group_type']) && $_GET['group_type']!='All Group'){
                    if ($history->group){
                        if($history->group->type == $_GET['group_type']){
                            $output[] = [
                                'group_name' => $history->group?$history->group->name:'',
                                'group_type' => ucfirst(str_replace('-',' ',$history->group?$history->group->type:'')),
                                'avatar'     => $history->account->avatar,
                                'social_icon'=> $history->account->type,
                                'icon_color' => $color,
                                'post_text'  => str_limit($history->post_text,70,'...'),
                                'time'       => $datetime->format('d M Y H:i a (e)')
                            ];
                        }
                    }
                }
            }
            if(!isset($_GET['group_type']) || $_GET['group_type']=='All Group'){
                $output[] = [
                    'group_name' => $history->group?$history->group->name:'',
                    'group_type' => ucfirst(str_replace('-',' ',$history->group?$history->group->type:'')),
                    'avatar'     => $history->account->avatar,
                    'social_icon'=> $history->account->type,
                    'icon_color' => $color,
                    'post_text'  => str_limit($history->post_text,70,'...'),
                    'time'       => $datetime->format('d M Y H:i a (e)')
                ];
            }

            if(count($output)==10){
                $result[] = $output;
                $output = [];
            }
        }
        $histories = $result;
        return view('pages.history',compact('histories','types'));
    }
}
