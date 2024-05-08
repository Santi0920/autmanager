// condicionNit.js
function esCondicionNit(codigoAutorizacion) {
    return codigoAutorizacion === '10M' || codigoAutorizacion === '11E'|| codigoAutorizacion=== '11F'|| codigoAutorizacion==='11M'|| codigoAutorizacion==='11N'|| codigoAutorizacion=== '11O'|| codigoAutorizacion==='11P'|| codigoAutorizacion==='11Q' || codigoAutorizacion=== '11R'|| codigoAutorizacion==='11S'|| codigoAutorizacion==='11T'  || codigoAutorizacion=== '11U'|| codigoAutorizacion==='11V'|| codigoAutorizacion==='11W' || codigoAutorizacion=== '11X'|| codigoAutorizacion==='11Y'|| codigoAutorizacion==='11Z'  ||codigoAutorizacion === '11AB'||  codigoAutorizacion=== '11AD'|| codigoAutorizacion==='11AE'|| codigoAutorizacion==='11AF'|| codigoAutorizacion==='11AG' || codigoAutorizacion === '10J' || codigoAutorizacion === '10G' || codigoAutorizacion === '10N' ||codigoAutorizacion === '10O' ||codigoAutorizacion === '10P' || codigoAutorizacion === '2300D' || codigoAutorizacion === '2400A' || codigoAutorizacion === '2400B' ||codigoAutorizacion === '1500A' || codigoAutorizacion === '19J' || codigoAutorizacion === '19D'|| codigoAutorizacion === '19E' || codigoAutorizacion === '1400A' || codigoAutorizacion === '1500C';
}


function condicionparamostrarNit(codigoAutorizacion) {
    return codigoAutorizacion !== '10M' && codigoAutorizacion !== '11E'&& codigoAutorizacion!== '11F'&& codigoAutorizacion!=='11M'&& codigoAutorizacion!=='11N'&& codigoAutorizacion!== '11O'&& codigoAutorizacion!=='11P'&& codigoAutorizacion!=='11Q' && codigoAutorizacion!== '11R'&& codigoAutorizacion!=='11S'&& codigoAutorizacion!=='11T'  && codigoAutorizacion!== '11U'&& codigoAutorizacion!=='11V'&& codigoAutorizacion!=='11W' && codigoAutorizacion!== '11X'&& codigoAutorizacion!=='11Y'&& codigoAutorizacion!=='11Z'  &&codigoAutorizacion !== '11AB'&&  codigoAutorizacion!== '11AD'&& codigoAutorizacion!=='11AE'&& codigoAutorizacion!=='11AF'&& codigoAutorizacion!=='11AG' && codigoAutorizacion !== '10J' && codigoAutorizacion !== '10G' && codigoAutorizacion !== '10N' &&codigoAutorizacion !== '10O' &&codigoAutorizacion !== '10P' && codigoAutorizacion !== '2300D' && codigoAutorizacion !== '2400A' && codigoAutorizacion !=='2400B' &&codigoAutorizacion !== '1500A' && codigoAutorizacion !== '19J' && codigoAutorizacion !== '19D' && codigoAutorizacion !== '19E' && codigoAutorizacion !== '1400A' &&  codigoAutorizacion !== '1500C';

}



