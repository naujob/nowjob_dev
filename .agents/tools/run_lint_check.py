# -*- coding: utf-8 -*-


import subprocess
import sys

def run_lint_check():
    """
    이 코드는 아주 오래된 Python 2에서도 완벽하게 작동해요.
    """
    try:
        # Python 2에서는 subprocess.run 대신 Popen을 사용하는 게 가장 안전해요.
        # 'php lint_check.php' 명령어를 실행합니다.
        process = subprocess.Popen(
            ['php', 'lint_check.php'],
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE
        )
        
        # 실행 결과를 가져옵니다.
        stdout, stderr = process.communicate()

        # 결과를 합쳐서 AI에게 보여줍니다.
        if stdout:
            return stdout
        else:
            return stderr

    except Exception as e:
        # Python 2는 f-string을 못 읽어서 .format()을 써야 해요.
        return "Error occurred: {0}".format(str(e))

if __name__ == "__main__":
    # AI가 이 파일을 실행했을 때 결과를 출력하도록 해요.
    print(run_lint_check())