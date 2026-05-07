import Button from 'react-bootstrap/esm/Button'
import style from './inscricoes.module.css'

const Inscricoes = () => {
    return (
        <>
       
        
        <h1 className={style.titulo}>Inscrições</h1>
        <Button className={style.button}>Novo</Button>
        </>
    )
}

export default Inscricoes