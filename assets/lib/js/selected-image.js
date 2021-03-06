import React from 'react';

const imgStyle = {
    display: 'block',
    transition: 'transform .135s cubic-bezier(0.0,0.0,0.2,1),opacity linear .15s'
};
const selectedImgStyle = {
    transform: 'translateZ(0px) scale3d(0.9, 0.9, 1)',
    transition: 'transform .135s cubic-bezier(0.0,0.0,0.2,1),opacity linear .15s'
};
const cont = {
    backgroundColor: '#eee',
    cursor: 'pointer',
    overflow: 'hidden',
    float: 'left',
    position: 'relative'
};

const SelectedImage = ({ index, onClick, photo, margin}) => {
    const sx = (100 - ((30 / photo.width) * 100)) / 100;
    const sy = (100 - ((30 / photo.height) * 100)) / 100;
    selectedImgStyle.transform = `translateZ(0px) scale3d(${sx}, ${sy}, 1)`;
    return (
        <div style={{margin, width:photo.width, ...cont}} className={'cont' + (!photo.selected ? ' selected' : '')}>
            <img style={photo.selected ? {...imgStyle, ...selectedImgStyle} : {...imgStyle}} {...photo} onClick={(e) => onClick(e, {index, photo})} />
            <style>
                {`.cont.selected:hover{outline:2px solid #06befa}`}
            </style>
        </div>
    )
};

export default SelectedImage;
