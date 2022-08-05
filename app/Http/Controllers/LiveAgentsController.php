<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class LiveAgentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allKeys    =   Redis::keys('*');
        $response   =   array();
        
        foreach($allKeys as $key => $val)
        {
            if(str_contains($val, 'laravel_live_agent'))
            {
                $response[] = json_decode(Redis::get($val), true);
            }
        }

        sort($response);

        return json_encode($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $now                                =   date("Y-m-d H:i:s");

        $live_agent_id                      =   !empty($request->input("live_agent_id"))                        ?   ''    :   $request->input("live_agent_id");
        $user                               =   !empty($request->input("user"))                                 ?   ''    :   $request->input("user");
        $server_ip                          =   !empty($request->input("server_ip"))                            ?   ''    :   $request->input("server_ip");
        $conf_exten                         =   !empty($request->input("conf_exten"))                           ?   ''    :   $request->input("conf_exten");
        $extension                          =   !empty($request->input("extension"))                            ?   ''    :   $request->input("extension");
        $status                             =   !empty($request->input("status"))                               ? 'PAUSED':   $request->input("status");
        $lead_id                            =   !empty($request->input("lead_id"))                              ?   ''    :   $request->input("lead_id");
        $campaign_id                        =   !empty($request->input("campaign_id"))                          ?   ''    :   $request->input("campaign_id");
        $uniqueid                           =   !empty($request->input("uniqueid"))                             ?   ''    :   $request->input("uniqueid");
        $callerid                           =   !empty($request->input("callerid"))                             ?   ''    :   $request->input("callerid");
        $channel                            =   !empty($request->input("channel"))                              ?   ''    :   $request->input("channel");
        $random_id                          =   !empty($request->input("random_id"))                            ?   ''    :   $request->input("random_id");
        $last_call_time                     =   !empty($request->input("last_call_time"))                       ?   ''    :   $request->input("last_call_time");
        $last_update_time                   =   $now;
        $last_call_finish                   =   !empty($request->input("last_call_finish"))                     ?   ''    :   $request->input("last_call_finish");
        $closer_campaigns                   =   !empty($request->input("closer_campaigns"))                     ?   ''    :   $request->input("closer_campaigns");
        $call_server_ip                     =   !empty($request->input("call_server_ip"))                       ?   ''    :   $request->input("call_server_ip");
        $user_level                         =   !empty($request->input("user_level"))                           ?   '0'   :   $request->input("user_level");
        $comments                           =   !empty($request->input("comments"))                             ?   ''    :   $request->input("comments");
        $campaign_weight                    =   !empty($request->input("campaign_weight"))                      ?   '0'   :   $request->input("campaign_weight");
        $calls_today                        =   !empty($request->input("calls_today"))                          ?   '0'   :   $request->input("calls_today");
        $external_hangup                    =   !empty($request->input("external_hangup"))                      ?   ''    :   $request->input("external_hangup");
        $external_status                    =   !empty($request->input("external_status"))                      ?   ''    :   $request->input("external_status");
        $external_pause                     =   !empty($request->input("external_pause"))                       ?   ''    :   $request->input("external_pause");
        $external_dial                      =   !empty($request->input("external_dial"))                        ?   ''    :   $request->input("external_dial");
        $external_ingroups                  =   !empty($request->input("external_ingroups"))                    ?   ''    :   $request->input("external_ingroups");
        $external_blended                   =   !empty($request->input("external_blended"))                     ?   '0'   :   $request->input("external_blended");
        $external_igb_set_user              =   !empty($request->input("external_igb_set_user"))                ?   ''    :   $request->input("external_igb_set_user");
        $external_update_fields             =   !empty($request->input("external_update_fields"))               ?   '0'   :   $request->input("external_update_fields");
        $external_update_fields_data        =   !empty($request->input("external_update_fields_data"))          ?   ''    :   $request->input("external_update_fields_data");
        $external_timer_action              =   !empty($request->input("external_timer_action"))                ?   ''    :   $request->input("external_timer_action");
        $external_timer_action_message      =   !empty($request->input("external_timer_action_message"))        ?   ''    :   $request->input("external_timer_action_message");
        $external_timer_action_seconds      =   !empty($request->input("external_timer_action_seconds"))        ?   '-1'  :   $request->input("external_timer_action_seconds");
        $agent_log_id                       =   !empty($request->input("agent_log_id"))                         ?   '0'   :   $request->input("agent_log_id");
        $last_state_change                  =   !empty($request->input("last_state_change"))                    ?   ''    :   $request->input("last_state_change");
        $agent_territories                  =   !empty($request->input("agent_territories"))                    ?   ''    :   $request->input("agent_territories");
        $outbound_autodial                  =   !empty($request->input("outbound_autodial"))                    ?   'N'   :   $request->input("outbound_autodial");
        $manager_ingroup_set                =   !empty($request->input("manager_ingroup_set"))                  ?   'N'   :   $request->input("manager_ingroup_set");
        $ra_user                            =   !empty($request->input("ra_user"))                              ?   ''    :   $request->input("ra_user");
        $ra_extension                       =   !empty($request->input("ra_extension"))                         ?   ''    :   $request->input("ra_extension");
        $external_dtmf                      =   !empty($request->input("external_dtmf"))                        ?   ''    :   $request->input("external_dtmf");
        $external_transferconf              =   !empty($request->input("external_transferconf"))                ?   ''    :   $request->input("external_transferconf");
        $external_park                      =   !empty($request->input("external_park"))                        ?   ''    :   $request->input("external_park");
        $external_timer_action_destination  =   !empty($request->input("external_timer_action_destination"))    ?   ''    :   $request->input("external_timer_action_destination");
        $on_hook_agent                      =   !empty($request->input("on_hook_agent"))                        ?   'N'   :   $request->input("on_hook_agent");
        $on_hook_ring_time                  =   !empty($request->input("on_hook_ring_time"))                    ?   '15'  :   $request->input("on_hook_ring_time");
        $ring_callerid                      =   !empty($request->input("ring_callerid"))                        ?   ''    :   $request->input("ring_callerid");
        $last_inbound_call_time             =   !empty($request->input("last_inbound_call_time"))               ?   ''    :   $request->input("last_inbound_call_time");
        $last_inbound_call_finish           =   !empty($request->input("last_inbound_call_finish"))             ?   ''    :   $request->input("last_inbound_call_finish");
        $campaign_grade                     =   !empty($request->input("campaign_grade"))                       ?   '1'   :   $request->input("campaign_grade");
        $external_recording                 =   !empty($request->input("external_recording"))                   ?   ''    :   $request->input("external_recording");
        $external_pause_code                =   !empty($request->input("external_pause_code"))                  ?   ''    :   $request->input("external_pause_code");
        $pause_code                         =   !empty($request->input("pause_code"))                           ?   ''    :   $request->input("pause_code");
        $preview_lead_id                    =   !empty($request->input("preview_lead_id"))                      ?   '0'   :   $request->input("preview_lead_id");
        $external_lead_id                   =   !empty($request->input("external_lead_id"))                     ?   '0'   :   $request->input("external_lead_id");
        $last_inbound_call_time_filtered    =   !empty($request->input("last_inbound_call_time_filtered"))      ?   ''    :   $request->input("last_inbound_call_time_filtered");
        $last_inbound_call_finish_filtered  =   !empty($request->input("last_inbound_call_finish_filtered"))    ?   ''    :   $request->input("last_inbound_call_finish_filtered");
        $dial_campaign_id                   =   !empty($request->input("dial_campaign_id"))                     ?   ''    :   $request->input("dial_campaign_id");
        
        $live_agent_id                      =   Redis::incr('live_agent:1');

        Redis::set('laravel_live_agent:live_agent_id:'. $live_agent_id, 
            json_encode([

                'live_agent_id'                         =>  $live_agent_id,
                'user'                                  =>  $user,
                'server_ip'                             =>  $server_ip,
                'conf_exten'                            =>  $conf_exten,
                'extension'                             =>  $extension,
                'status'                                =>  $status,
                'lead_id'                               =>  $lead_id,
                'campaign_id'                           =>  $campaign_id,
                'uniqueid'                              =>  $uniqueid,
                'callerid'                              =>  $callerid,
                'channel'                               =>  $channel,
                'random_id'                             =>  $random_id,
                'last_call_time'                        =>  $last_call_time,
                'last_update_time'                      =>  $last_update_time,
                'last_call_finish'                      =>  $last_call_finish,
                'closer_campaigns'                      =>  $closer_campaigns,
                'call_server_ip'                        =>  $call_server_ip,
                'user_level'                            =>  $user_level,
                'comments'                              =>  $comments,
                'campaign_weight'                       =>  $campaign_weight,
                'calls_today'                           =>  $calls_today,
                'external_hangup'                       =>  $external_hangup,
                'external_status'                       =>  $external_status,
                'external_pause'                        =>  $external_pause,
                'external_dial'                         =>  $external_dial,
                'external_ingroups'                     =>  $external_ingroups,
                'external_blended'                      =>  $external_blended,
                'external_igb_set_user'                 =>  $external_igb_set_user,
                'external_update_fields'                =>  $external_update_fields,
                'external_update_fields_data'           =>  $external_update_fields_data,
                'external_timer_action'                 =>  $external_timer_action,
                'external_timer_action_message'         =>  $external_timer_action_message,
                'external_timer_action_seconds'         =>  $external_timer_action_seconds,
                'agent_log_id'                          =>  $agent_log_id,
                'last_state_change'                     =>  $last_state_change,
                'agent_territories'                     =>  $agent_territories,
                'outbound_autodial'                     =>  $outbound_autodial,
                'manager_ingroup_set'                   =>  $manager_ingroup_set,
                'ra_user'                               =>  $ra_user,
                'ra_extension'                          =>  $ra_extension,
                'external_dtmf'                         =>  $external_dtmf,
                'external_transferconf'                 =>  $external_transferconf,
                'external_park'                         =>  $external_park,
                'external_timer_action_destination'     =>  $external_timer_action_destination,
                'on_hook_agent'                         =>  $on_hook_agent,
                'on_hook_ring_time'                     =>  $on_hook_ring_time,
                'ring_callerid'                         =>  $ring_callerid,
                'last_inbound_call_time'                =>  $last_inbound_call_time,
                'last_inbound_call_finish'              =>  $last_inbound_call_finish,
                'campaign_grade'                        =>  $campaign_grade,
                'external_recording'                    =>  $external_recording,
                'external_pause_code'                   =>  $external_pause_code,
                'pause_code'                            =>  $pause_code,
                'preview_lead_id'                       =>  $preview_lead_id,
                'external_lead_id'                      =>  $external_lead_id,
                'last_inbound_call_time_filtered'       =>  $last_inbound_call_time_filtered,
                'last_inbound_call_finish_filtered'     =>  $last_inbound_call_finish_filtered,
                'dial_campaign_id'                      =>  $dial_campaign_id
            ])
        );

        $response = Redis::get('laravel_live_agent:live_agent_id:'. $live_agent_id);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($live_agent_id)
    {
        $response = Redis::get('laravel_live_agent:live_agent_id:'. $live_agent_id);

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $live_agent_id)
    {
        $response = Redis::get('laravel_live_agent:live_agent_id:'. $live_agent_id);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($live_agent_id)
    {
        $response = Redis::del('laravel_live_agent:live_agent_id:'. $live_agent_id);

        return $response;
    }
}
