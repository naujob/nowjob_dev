/**
 * 방 정보 확인 및 생성 스크립트
 * rm_idx: 576 방 정보 확인 및 생성
 */

const $db = require('./chatting/chat/database/database.js');
const $common = require('./chatting/chat/common/common.js');

async function checkAndCreateRoom() {
    try {
        console.log('=== 방 정보 확인 및 생성 시작 ===');
        
        // 데이터베이스 초기화
        $db.init('데이터베이스 연결');
        
        // 잠시 대기 (연결 완료 대기)
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        const rm_idx = 576;
        console.log(`방 정보 조회 시작 - rm_idx: ${rm_idx}`);
        
        // 방 정보 조회
        const room_info = await $db.cs_room.findOne({rm_idx: rm_idx});
        console.log('방 정보 조회 결과:', room_info);
        
        if (!room_info) {
            console.log('방 정보가 없습니다. 새로 생성합니다.');
            
            // 새 방 정보 생성
            const new_room = {
                rm_idx: rm_idx,
                rc_idx: 0,
                rm_type: "recruit",
                rm_img: "",
                rm_subject: "채용공고 채팅방",
                rm_desc: "채용공고 관련 채팅방",
                rm_last_msg: "",
                rm_last_dt: $common.getCurrentDate(),
                rm_users: [
                    {user_id: "01083092881"},
                    {user_id: "01029480913"}
                ],
                user_info: [
                    {user_id: "01083092881"},
                    {user_id: "01029480913"}
                ],
                rm_status: "N",
                rm_messages: []
            };
            
            console.log('새 방 정보 생성:', new_room);
            
            // 방 정보 삽입
            const result = await $db.cs_room.insertOne(new_room);
            console.log('방 정보 삽입 결과:', result);
            
            if (result.insertedId) {
                console.log('방 정보 생성 성공!');
            } else {
                console.log('방 정보 생성 실패!');
            }
        } else {
            console.log('방 정보가 이미 존재합니다.');
            console.log('방 정보 상세:', JSON.stringify(room_info, null, 2));
        }
        
        // 방 정보 재조회
        const updated_room = await $db.cs_room.findOne({rm_idx: rm_idx});
        console.log('업데이트된 방 정보:', updated_room);
        
    } catch (error) {
        console.error('에러 발생:', error);
    }
}

// 스크립트 실행
checkAndCreateRoom();
