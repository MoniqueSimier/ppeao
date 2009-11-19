// Place your application-specific JavaScript functions and classes here
// This file is automatically included by javascript_include_tag :defaults
function selectionnerToutesEnquetes()
{
    var frm = document.getElementById('frmSelectCampagnes');
    var campagnes = frm.elements['campagnes[]'];
    for (i = 0; i < campagnes.length; i++)
    campagnes[i].checked = true;
    activerBoutonExecuter();
}

function deselectionnerToutesEnquetes()
{
    var frm = document.getElementById('frmSelectCampagnes');
    var campagnes = frm.elements['campagnes[]'];
    for (i = 0; i < campagnes.length; i++)
    campagnes[i].checked = false;
    desactiverBoutonExecuter();
}

function effacerCampagnes()
{
    document.location = '/migration_pechexp/campagnes';
}

function activerBoutonExecuter()
{
    var btExecuter = document.getElementById('btExecuterCampagnes');
    btExecuter.disabled = false;
}

function desactiverBoutonExecuter()
{
    var btExecuter = document.getElementById('btExecuterCampagnes');
    btExecuter.disabled = true;
}

function boutonExecuterManager()
{
    var actif = false;
    var frm = document.getElementById('frmSelectCampagnes');
    var campagnes = frm.elements['campagnes[]'];
    i=0;
    while((i< campagnes.length)&&(actif==false))
    {
    if(campagnes[i].checked)
        {
            actif = true;
        }
    i++;
    }
    if (actif)
    {
        activerBoutonExecuter();
    }
    else
    {
        desactiverBoutonExecuter();
    }
    
     
}

function boutonOKSecteurManager()
{
    var actif = false;
    var frm = document.getElementById('frmChoixSecteurs');
    var secteurs = frm.elements['secteurs[]'];
    i=0;
    while((i< secteurs.options.length)&&(actif==false))
    {
    if(secteurs.options[i].selected)
        {
            actif = true;
        }
    i++;
    }
    if (actif)
    {
        var bouton = document.getElementById('btOKSecteur');
        bouton.disabled = false;
        /*bouton.href="#";*/
        
    }
    else
    {
        var bouton = document.getElementById('btOKSecteur');
        bouton.disabled = false;
        //bouton.removeAttribute("href");
    }
    
}

function boutonOKAgglomerationManager()
{
    var actif = false;
    var frm = document.getElementById('frmChoixAgglomerations');
    var agglomerations = frm.elements['agglomerations[]'];
    i=0;
    while((i< agglomerations.options.length)&&(actif==false))
    {
    if(agglomerations.options[i].selected)
        {
            actif = true;
        }
    i++;
    }
    if (actif)
    {
        var bouton = document.getElementById('btOKAgglomeration');
        bouton.disabled = false;
        //bouton.href="#";
        
    }
    else
    {
        var bouton = document.getElementById('btOKAgglomeration');
        bouton.disabled = true;
        //bouton.removeAttribute("href");
    }
    
}


function selectionnerToutesPeriodes()
{
    var frm = document.getElementById('frmChoixPeriodes');
    var periodes = frm.elements['periodes[]'];
    for (i = 0; i < periodes.length; i++)
    periodes[i].checked = true;
    
    var bouton = document.getElementById('btExecuterEnquete');
    bouton.disabled = false;
    
    //activerBoutonExecuter();
}

function deselectionnerToutesPeriodes()
{
    var frm = document.getElementById('frmChoixPeriodes');
    var periodes = frm.elements['periodes[]'];
    for (i = 0; i < periodes.length; i++)
    periodes[i].checked = false;
    
    var bouton = document.getElementById('btExecuterEnquete');
    bouton.disabled = true;
    
    //desactiverBoutonExecuter();
}

function effacerEnquetes()
{
    document.location = '/migration_pechart/enquetes';
}

function boutonExecuterEnquetesManager()
{
    var actif = false;
    var frm = document.getElementById('frmChoixPeriodes');
    var periodes = frm.elements['periodes[]'];
    i=0;
    while((i< periodes.length)&&(actif==false))
    {
    if(periodes[i].checked)
        {
            actif = true;
        }
    i++;
    }
    if (actif)
    {
        var bouton = document.getElementById('btExecuterEnquete');
        bouton.disabled = false;
    }
    else
    {
        var bouton = document.getElementById('btExecuterEnquete');
        bouton.disabled = true;
    }    
     
}

function submitForm(form)
{
    var formulaire = document.getElementById(form);
    formulaire.submit();
}


function selectionnerTousSecteurs()
{
    var frm = document.getElementById('frmChoixSecteurs');
    var secteurs = frm.elements['secteurs[]'];
    for (i = 0; i < secteurs.length; i++)
    secteurs[i].selected = true;
    var bouton = document.getElementById('btOKSecteur');
    bouton.disabled = false;
    //bouton.href="#";
    
}

function selectionnerToutesAgglomerations()
{
    var frm = document.getElementById('frmChoixAgglomerations');
    var agglomerations = frm.elements['agglomerations[]'];
    for (i = 0; i < agglomerations.length; i++)
    agglomerations[i].selected = true;
    var bouton = document.getElementById('btOKAgglomeration');
    bouton.disabled = false;
    //bouton.href="#";
    
}

function confirmInitJournal()
{
    if (confirm("Vous êtes sur le point d'archiver vos logs.\n Voulez vous continuer ?" ))
    {
        document.location='/journal/initialiser';
    }
}

function confirmThenGo(message, formulaire)
{
    if (confirm(message))
    {
        document.getElementById(formulaire).submit();
    }
}


