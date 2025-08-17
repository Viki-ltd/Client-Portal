@echo off
ECHO Starting Full Client Portal Development Environment...
ECHO.

:: --- Step 1: Launch the XAMPP Control Panel ---
ECHO Launching XAMPP Control Panel...
start "XAMPP" "C:\xampp\xampp-control.exe"
ECHO.

:: --- Step 2: (Optional) Start the Ollama AI Model Server ---
ECHO Launching Ollama Server in a new window...
start "Ollama Server" cmd /k ollama serve
ECHO.

:: --- Step 3: Launch the Python Flask Backend Server ---
ECHO Launching Python Flask Backend Server...
:: CORRECTED: This now points to your actual python_llm_service folder
:: and uses the correct commands to start that specific server.
start "Python Backend Server" cmd /k "cd C:\xampp\htdocs\client-portal\python_llm_service && .\venv\Scripts\activate && python app.py"
ECHO.

:: --- Step 4: Launch the Web Browser ---
ECHO Waiting for servers to initialize...
timeout /t 5 /nobreak >nul
ECHO Launching project in web browser...
start http://localhost/client-portal/public/ai-narrative-architect
ECHO.

ECHO All services launched.
ECHO Please ensure Apache and MySQL are running in the XAMPP panel.