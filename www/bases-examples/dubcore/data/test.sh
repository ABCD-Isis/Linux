./mx null count=1 "proc='<99>','Test record 1','</99>'" append=dubcore now -all
./mx dubcore fst=@ ifupd=dubcore now -all
./mx null count=1 "proc='<99>','Test record 2','</99>'" append=dubcore now -all
./mx dubcore fst=@ ifupd=dubcore now -all


