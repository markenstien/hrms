<?php

    use Services\QRTokenService;
    load(['QRTokenService'], APPROOT.DS.'services');
    class QRTokenLogin extends Controller
    {
        public function getLastest(){
            $qrToken = QRTokenService::getLatest(QRTokenService::LOGIN_TOKEN);
            $qrToken->full_path = base64_decode($qrToken->full_path);
            $qrToken->src_url = base64_decode($qrToken->src_url);
            ee($qrToken);
        }
    }