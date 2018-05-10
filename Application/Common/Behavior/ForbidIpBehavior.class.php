<?php
namespace Common\Behavior;

class ForbidIpBehavior {
    public function run(&$params){
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;
        $this->forbidIp();
    }

    private function forbidIp(){
        // $IP = get_client_ip();
        // $iplist = M('ipban')->field('ip')->select();
        // for($i=0;$i<count($iplist);$i++){
        //     $list[$i] = long2ip($iplist[$i]['ip']);
        // }
        // if(in_array($IP,$list)){
        //     exit('You don\'t have permission to access!');
        // }
    }
}

