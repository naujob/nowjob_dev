/**
 * 사용자 정보 및 FCM 토큰 설정 스크립트
 * 푸시 알림을 위한 사용자 정보 생성
 */

const $db = require('./chatting/chat/database/database.js');
const $common = require('./chatting/chat/common/common.js');

async function setupUsers() {
    try {
        console.log('=== 사용자 정보 설정 시작 ===');
        
        // 데이터베이스 초기화
        $db.init('데이터베이스 연결');
        
        // 잠시 대기 (연결 완료 대기)
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        // 사용자 정보 설정
        const users = [
            {
                user_id: "01083092881",
                user_nick: "사용자1",
                user_img: "",
                user_desc: "채팅 사용자1",
                user_status: "Y", // 접속 상태
                token: "test_fcm_token_1", // FCM 토큰 (실제 토큰으로 교체 필요)
                socket_id: "",
                reg_dt: $common.getCurrentDate()
            },
            {
                user_id: "01029480913", 
                user_nick: "사용자2",
                user_img: "",
                user_desc: "채팅 사용자2",
                user_status: "N", // 비접속 상태 (푸시 알림 대상)
                token: "test_fcm_token_2", // FCM 토큰 (실제 토큰으로 교체 필요)
                socket_id: "",
                reg_dt: $common.getCurrentDate()
            }
        ];
        
        for (const user of users) {
            console.log(`사용자 정보 설정: ${user.user_id}`);
            
            // 기존 사용자 정보 확인
            const existing_user = await $db.cs_user.findOne({user_id: user.user_id});
            
            if (existing_user) {
                console.log(`사용자 ${user.user_id} 이미 존재. 업데이트합니다.`);
                // 기존 사용자 정보 업데이트
                const update_result = await $db.cs_user.updateOne(
                    {user_id: user.user_id},
                    {
                        $set: {
                            user_nick: user.user_nick,
                            user_img: user.user_img,
                            user_desc: user.user_desc,
                            user_status: user.user_status,
                            token: user.token,
                            socket_id: user.socket_id,
                            reg_dt: user.reg_dt
                        }
                    }
                );
                console.log(`사용자 ${user.user_id} 업데이트 결과:`, update_result);
            } else {
                console.log(`사용자 ${user.user_id} 새로 생성합니다.`);
                // 새 사용자 정보 생성
                const insert_result = await $db.cs_user.insertOne(user);
                console.log(`사용자 ${user.user_id} 생성 결과:`, insert_result);
            }
        }
        
        // 사용자 정보 확인
        console.log('\n=== 사용자 정보 확인 ===');
        for (const user of users) {
            const user_info = await $db.cs_user.findOne({user_id: user.user_id});
            console.log(`사용자 ${user.user_id} 정보:`, {
                user_nick: user_info?.user_nick,
                user_status: user_info?.user_status,
                token: user_info?.token ? user_info.token.substring(0, 20) + '...' : '없음'
            });
        }
        
        console.log('\n=== 사용자 정보 설정 완료 ===');
        
    } catch (error) {
        console.error('에러 발생:', error);
    }
}

// 스크립트 실행
setupUsers();
