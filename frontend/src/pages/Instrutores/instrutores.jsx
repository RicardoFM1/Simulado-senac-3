import Button from 'react-bootstrap/esm/Button'
import style from './instrutores.module.css'

const Instrutores = () => {
    return (
        <>
         <h1 className={style.titulo}>Instrutores</h1>
         <Button className={style.button}>Novo</Button>
        </>
    )
}

export default Instrutores