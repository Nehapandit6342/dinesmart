@echo off
cd /d C:\xampp\htdocs\dinesmart
git add .
git commit -m "Auto update on %date% at %time%"
git push origin main
pause
